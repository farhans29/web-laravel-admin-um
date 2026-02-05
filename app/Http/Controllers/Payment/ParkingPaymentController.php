<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParkingFeeTransaction;
use Illuminate\Support\Facades\Auth;

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
                    'image' => $img->image,
                    'image_type' => $img->image_type,
                    'description' => $img->description,
                ];
            })->values()
        ]);
    }
}
