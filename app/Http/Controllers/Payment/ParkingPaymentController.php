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
            DB::beginTransaction();

            $transaction = ParkingFeeTransaction::with('parkingFee')->findOrFail($id);

            // Store previous status to check if we need to decrement quota
            $wasPaid = $transaction->transaction_status === 'paid';

            $transaction->update([
                'transaction_status' => 'rejected',
                'notes' => $request->notes,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'updated_by' => Auth::id(),
            ]);

            // If transaction was paid, decrement the quota
            if ($wasPaid && $transaction->parkingFee && $transaction->parkingFee->capacity > 0) {
                $transaction->parkingFee->decrementQuota();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran parkir berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

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
            'order_id' => 'required|exists:t_transactions,order_id',
            'parking_type' => 'required|in:car,motorcycle',
            'vehicle_plate' => 'required|string|max:50',
            'fee_amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'payment_proof' => 'required|image|mimes:jpeg,jpg|max:5120', // 5MB in kilobytes
            'notes' => 'nullable|string|max:1000',
        ], [
            'order_id.required' => 'Please select a booking order',
            'order_id.exists' => 'Selected booking order not found',
            'payment_proof.image' => 'Payment proof must be an image file',
            'payment_proof.mimes' => 'Payment proof must be a JPG/JPEG file',
            'payment_proof.max' => 'Payment proof size must not exceed 5MB',
        ]);

        try {
            DB::beginTransaction();

            // Get transaction details from order_id
            $bookingTransaction = \App\Models\Transaction::where('order_id', $request->order_id)->firstOrFail();

            // Get property details
            $property = \App\Models\Property::findOrFail($bookingTransaction->property_id);

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
            if ($lastTransaction && $lastTransaction->invoice_id) {
                // Extract number from last invoice_id (format: 0001/PRK-XX/KGA-INV/I/2026)
                preg_match('/^(\d+)\//', $lastTransaction->invoice_id, $matches);
                if (!empty($matches[1])) {
                    $sequentialNumber = intval($matches[1]) + 1;
                }
            }

            // Format sequential number with leading zeros
            $formattedNumber = str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);

            // Generate invoice ID: 0001/PRK-XX/KGA-INV/I/2026
            $invoiceId = sprintf(
                '%s/PRK-%s/KGA-INV/%s/%s',
                $formattedNumber,
                $propertyInitials,
                $romanMonth,
                $year
            );

            // Get or create parking_fee
            $parkingFee = ParkingFee::where('property_id', $bookingTransaction->property_id)
                ->where('parking_type', $request->parking_type)
                ->where('status', 1)
                ->first();

            // Track if parking fee was auto-created
            $parkingFeeAutoCreated = false;

            // If no parking fee exists, create one with unlimited capacity (capacity = 0)
            if (!$parkingFee) {
                $parkingFeeAutoCreated = true;

                $parkingFee = ParkingFee::create([
                    'property_id' => $bookingTransaction->property_id,
                    'parking_type' => $request->parking_type,
                    'fee' => $request->fee_amount,
                    'capacity' => 0, // 0 = unlimited parking
                    'quota_used' => 0,
                    'status' => 1,
                    'created_by' => Auth::id(),
                ]);

                // Refresh to get the auto-generated ID
                $parkingFee->refresh();

                // Log creation for debugging
                \Log::info('Auto-created parking fee', [
                    'property_id' => $bookingTransaction->property_id,
                    'parking_type' => $request->parking_type,
                    'capacity' => 0,
                    'note' => 'Unlimited parking (capacity = 0). Set capacity in Parking Fee Management to enable quota control.'
                ]);
            }

            // Check quota availability before creating transaction (only if capacity is set)
            // capacity = 0 means unlimited parking, no quota check needed
            if ($parkingFee->capacity > 0 && !$parkingFee->hasAvailableQuota()) {
                throw new \Exception(
                    'Parking quota is full for ' . ucfirst($request->parking_type) .
                    '. Available: 0, Capacity: ' . $parkingFee->capacity .
                    '. Please wait for check-out or increase capacity in Parking Fee Management.'
                );
            }

            // Create parking fee transaction with status 'paid' and already verified
            $transaction = ParkingFeeTransaction::create([
                'property_id' => $bookingTransaction->property_id,
                'parking_fee_id' => $parkingFee->idrec,
                'invoice_id' => $invoiceId,
                'order_id' => $request->order_id,
                'user_id' => $bookingTransaction->user_id,
                'user_name' => $bookingTransaction->user_name,
                'user_phone' => $bookingTransaction->user_phone_number,
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

                // Generate unique filename (sanitize invoiceId by replacing / with -)
                $sanitizedInvoiceId = str_replace('/', '-', $invoiceId);
                $filename = $sanitizedInvoiceId . '_' . time() . '.' . $image->getClientOriginalExtension();

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

            // Increment quota used (only if capacity is set)
            if ($parkingFee->capacity > 0) {
                $parkingFee->incrementQuota();
            }

            DB::commit();

            // Build success message
            $message = 'Parking payment added successfully';
            if ($parkingFeeAutoCreated) {
                $message .= '. Parking fee auto-created with unlimited capacity (0). You can set parking quota limit in Parking Fee Management.';
            } elseif ($parkingFee->capacity === 0) {
                $message .= '. This property has unlimited parking (no quota limit).';
            } else {
                $remaining = $parkingFee->available_quota;
                $message .= ". Parking quota: {$remaining}/{$parkingFee->capacity} available.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $transaction,
                'parking_info' => [
                    'auto_created' => $parkingFeeAutoCreated,
                    'capacity' => $parkingFee->capacity,
                    'quota_used' => $parkingFee->quota_used,
                    'available_quota' => $parkingFee->available_quota,
                    'is_unlimited' => $parkingFee->capacity === 0,
                ]
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
                ->get(['idrec', 'parking_type', 'fee', 'capacity', 'quota_used'])
                ->map(function($fee) {
                    return [
                        'idrec' => $fee->idrec,
                        'parking_type' => $fee->parking_type,
                        'fee' => $fee->fee,
                        'capacity' => $fee->capacity,
                        'quota_used' => $fee->quota_used,
                        'available_quota' => $fee->available_quota,
                        'quota_percentage' => $fee->quota_usage_percentage,
                    ];
                });

            return response()->json($parkingFees);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load parking fees: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCheckedInOrders(Request $request)
    {
        try {
            $propertyId = $request->get('property_id');

            // Query dari t_booking dengan join ke t_transactions
            $query = \App\Models\Booking::whereNotNull('check_in_at')
                ->whereNull('check_out_at') // Masih check-in, belum check-out
                ->where('status', 1) // Active booking
                ->with(['transaction' => function($q) {
                    $q->where('transaction_status', 'paid')
                        ->where('status', 1);
                }, 'property', 'room']);

            // Filter by property if user is site admin
            $user = Auth::user();
            if ($user->isSite() && $user->property_id) {
                $query->where('property_id', $user->property_id);
            } elseif ($propertyId) {
                $query->where('property_id', $propertyId);
            }

            $orders = $query->orderBy('check_in_at', 'desc')
                ->get()
                ->filter(function($booking) {
                    // Filter only bookings with paid transaction
                    return $booking->transaction && $booking->transaction->transaction_status === 'paid';
                })
                ->map(function($booking) {
                    return [
                        'order_id' => $booking->order_id,
                        'property_id' => $booking->property_id,
                        'user_name' => $booking->transaction->user_name ?? $booking->user_name,
                        'user_phone' => $booking->transaction->user_phone_number ?? $booking->user_phone_number,
                        'room_name' => $booking->room->name ?? '-',
                        'property_name' => $booking->property->name ?? '-',
                        'check_in' => $booking->check_in_at ? $booking->check_in_at->format('d M Y') : '',
                        'check_out' => $booking->transaction && $booking->transaction->check_out
                            ? $booking->transaction->check_out->format('d M Y')
                            : '-',
                        'display_text' => $booking->order_id . ' - ' . ($booking->transaction->user_name ?? $booking->user_name) . ' (' . ($booking->room->name ?? '-') . ')',
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load checked-in orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check out parking - Release quota
     */
    public function checkout(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $transaction = ParkingFeeTransaction::with('parkingFee')->findOrFail($id);

            // Only paid transactions can be checked out
            if ($transaction->transaction_status !== 'paid') {
                throw new \Exception('Only paid parking can be checked out');
            }

            // Update transaction to completed/checked-out
            $transaction->update([
                'transaction_status' => 'completed',
                'updated_by' => Auth::id(),
            ]);

            // Decrement quota (free up the parking space)
            if ($transaction->parkingFee && $transaction->parkingFee->capacity > 0) {
                $transaction->parkingFee->decrementQuota();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Parking checked out successfully. Quota has been released.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to checkout parking: ' . $e->getMessage()
            ], 500);
        }
    }
}
