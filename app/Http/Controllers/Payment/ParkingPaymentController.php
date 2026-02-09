<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParkingFeeTransaction;
use App\Models\ParkingFeeTransactionImage;
use App\Models\ParkingFee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ParkingPaymentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = ParkingFeeTransaction::with(['property', 'parkingFee', 'images', 'verifiedBy', 'createdBy'])
            ->orderBy('created_at', 'desc');

        $user = Auth::user();
        if ($user->isSite() && $user->property_id) {
            $query->where('property_id', $user->property_id);
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('order_id', 'like', "%{$request->search}%")
                    ->orWhere('user_name', 'like', "%{$request->search}%")
                    ->orWhere('vehicle_plate', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('transaction_status', $request->status);
        }

        if ($request->has('parking_type') && !empty($request->parking_type)) {
            $query->where('parking_type', $request->parking_type);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $parkingTransactions = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        if ($request->ajax() || $request->header('X-Requested-With') == 'XMLHttpRequest') {
            return view('pages.payment.parking.partials.parking-payment_table', compact('parkingTransactions'));
        }

        return view('pages.payment.parking.index', compact('parkingTransactions'));
    }

    public function filter(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = ParkingFeeTransaction::with(['property', 'parkingFee', 'images', 'verifiedBy', 'createdBy'])
            ->orderBy('created_at', 'desc');

        $user = Auth::user();
        if ($user->isSite() && $user->property_id) {
            $query->where('property_id', $user->property_id);
        }

        if (!empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('order_id', 'like', "%{$request->search}%")
                    ->orWhere('user_name', 'like', "%{$request->search}%")
                    ->orWhere('vehicle_plate', 'like', "%{$request->search}%");
            });
        }

        if (!empty($request->status)) {
            $query->where('transaction_status', $request->status);
        }

        if (!empty($request->parking_type)) {
            $query->where('parking_type', $request->parking_type);
        }

        if (!empty($request->date_from)) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if (!empty($request->date_to)) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $parkingTransactions = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->appends($request->all());

        return response()->json([
            'html' => view('pages.payment.parking.partials.parking-payment_table', compact('parkingTransactions'))->render(),
            'pagination' => $perPage !== 'all' && $parkingTransactions instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? $parkingTransactions->links()->toHtml()
                : ''
        ]);
    }

    public function approve(Request $request, $id)
    {
        try {
            $transaction = ParkingFeeTransaction::findOrFail($id);

            $transaction->update([
                'transaction_status' => 'paid',
                'paid_at' => now(),
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran parkir berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string|min:10',
        ]);

        try {
            $transaction = ParkingFeeTransaction::findOrFail($id);

            $transaction->update([
                'transaction_status' => 'rejected',
                'notes' => $request->notes,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran parkir berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewProof($id)
    {
        $transaction = ParkingFeeTransaction::with('images')->findOrFail($id);

        $images = $transaction->images->where('status', 1);

        if ($images->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada bukti pembayaran'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'images' => $images->map(function ($img) {
                return [
                    'id' => $img->idrec,
                    'image_url' => asset('storage/' . $img->image),
                    'image_type' => $img->image_type,
                    'description' => $img->description,
                ];
            })->values()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:m_properties,idrec',
            'parking_type' => 'required|in:car,motorcycle',
            'vehicle_plate' => 'required|string|max:50',
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|string|max:20',
            'fee_amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'payment_proof' => 'required|image|mimes:jpeg,jpg|max:5120', // 5MB in kilobytes
            'notes' => 'nullable|string|max:1000',
        ], [
            'payment_proof.image' => 'Payment proof must be an image file',
            'payment_proof.mimes' => 'Payment proof must be a JPG/JPEG file',
            'payment_proof.max' => 'Payment proof size must not exceed 5MB',
        ]);

        try {
            DB::beginTransaction();

            // Get property details
            $property = \App\Models\Property::findOrFail($request->property_id);

            // Generate property initials (first letter of each word)
            $propertyWords = explode(' ', $property->name);
            $propertyInitials = '';
            foreach ($propertyWords as $word) {
                if (!empty($word)) {
                    $propertyInitials .= strtoupper(substr($word, 0, 1));
                }
            }
            if (strlen($propertyInitials) > 3) {
                $propertyInitials = substr($propertyInitials, 0, 3);
            }

            // Get current month and year
            $transactionDate = \Carbon\Carbon::parse($request->transaction_date);
            $month = $transactionDate->month;
            $year = $transactionDate->year;

            // Convert month to Roman numeral
            $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romanMonths[$month - 1];

            // Get sequential number for this month/year
            $lastTransaction = ParkingFeeTransaction::whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->orderBy('created_at', 'desc')
                ->first();

            $sequentialNumber = 1;
            if ($lastTransaction && $lastTransaction->order_id) {
                // Extract number from last order_id (format: 0001/PRK-XX/KGA-INV/I/2026)
                preg_match('/^(\d+)\//', $lastTransaction->order_id, $matches);
                if (!empty($matches[1])) {
                    $sequentialNumber = intval($matches[1]) + 1;
                }
            }

            // Format sequential number with leading zeros
            $formattedNumber = str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);

            // Generate order ID: 0001/PRK-XX/KGA-INV/I/2026
            $orderId = sprintf(
                '%s/PRK-%s/KGA-INV/%s/%s',
                $formattedNumber,
                $propertyInitials,
                $romanMonth,
                $year
            );

            // Get or create parking_fee
            $parkingFee = ParkingFee::where('property_id', $request->property_id)
                ->where('parking_type', $request->parking_type)
                ->where('status', 1)
                ->first();

            // If no parking fee exists, create one
            if (!$parkingFee) {
                $parkingFee = ParkingFee::create([
                    'property_id' => $request->property_id,
                    'parking_type' => $request->parking_type,
                    'fee' => $request->fee_amount,
                    'capacity' => 0,
                    'status' => 1,
                    'created_by' => Auth::id(),
                ]);

                // Refresh to get the auto-generated ID
                $parkingFee->refresh();
            }

            // Create parking fee transaction with status 'paid' and already verified
            $transaction = ParkingFeeTransaction::create([
                'property_id' => $request->property_id,
                'parking_fee_id' => $parkingFee->idrec,
                'order_id' => $orderId,
                'user_name' => $request->user_name,
                'user_phone' => $request->user_phone,
                'parking_type' => $request->parking_type,
                'vehicle_plate' => strtoupper($request->vehicle_plate),
                'fee_amount' => $request->fee_amount,
                'transaction_date' => $request->transaction_date,
                'transaction_status' => 'paid', // Already paid
                'paid_at' => now(),
                'verified_by' => Auth::id(), // Already verified
                'verified_at' => now(),
                'notes' => $request->notes,
                'status' => 1,
                'created_by' => Auth::id(),
            ]);

            // Handle image upload - Save as file
            if ($request->hasFile('payment_proof')) {
                $image = $request->file('payment_proof');

                // Create directory if not exists
                $uploadPath = storage_path('app/public/parking_payments');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Generate unique filename (sanitize orderId by replacing / with -)
                $sanitizedOrderId = str_replace('/', '-', $orderId);
                $filename = $sanitizedOrderId . '_' . time() . '.' . $image->getClientOriginalExtension();

                // Store the file
                $image->move($uploadPath, $filename);

                // Save file path to database
                ParkingFeeTransactionImage::create([
                    'parking_transaction_id' => $transaction->idrec,
                    'image' => 'parking_payments/' . $filename, // Store relative path
                    'image_type' => $image->getClientOriginalExtension(),
                    'description' => 'Payment proof uploaded by admin',
                    'status' => 1,
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Parking payment added successfully',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add parking payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getParkingFees($propertyId)
    {
        try {
            $parkingFees = ParkingFee::where('property_id', $propertyId)
                ->where('status', 1)
                ->get(['idrec', 'parking_type', 'fee']);

            return response()->json($parkingFees);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load parking fees: ' . $e->getMessage()
            ], 500);
        }
    }
}
