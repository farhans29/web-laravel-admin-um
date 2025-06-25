<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['booking', 'payment', 'user'])
            ->where('status', 0)
            ->whereHas('payment') // Hanya ambil yang punya relasi di t_payment
            ->orderBy('transaction_date', 'desc')
            ->paginate(8);

        return view('pages.payment.pay.index', compact('transactions'));
    }

    public function filter(Request $request)
    {
        $query = Transaction::with(['booking', 'payment', 'user'])
            ->orderBy('transaction_date', 'desc');

        if ($request->has('status') && $request->status != 'all') {
            $query->where('transaction_status', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('user_name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('booking.property', function ($propertyQuery) use ($search) {
                        $propertyQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $transactions = $query->paginate(8);

        return response()->json([
            'transactions' => $transactions,
            'pagination' => $transactions->links()->toHtml()
        ]);
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Update transaction
        $transaction->update([
            'transaction_status' => 'paid',
            'paid_at' => now()
        ]);

        // Create or update payment record
        Payment::updateOrCreate(
            ['order_id' => $transaction->order_id],
            [
                'property_id' => $transaction->property_id,
                'room_id' => $transaction->room_id,
                'user_id' => $transaction->user_id,
                'grandtotal_price' => $transaction->grandtotal_price,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'payment_status' => 'paid',
                'updated_at' => now()
            ]
        );

        return redirect()->back()->with('success', 'Payment approved successfully');
    }

    public function reject($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Update transaction
        $transaction->update([
            'transaction_status' => 'rejected',
            'paid_at' => now()
        ]);

        // Create or update payment record
        Payment::updateOrCreate(
            ['order_id' => $transaction->order_id],
            [
                'property_id' => $transaction->property_id,
                'room_id' => $transaction->room_id,
                'user_id' => $transaction->user_id,
                'grandtotal_price' => $transaction->grandtotal_price,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'payment_status' => 'rejected',
                'notes' => request('rejectNote'), // Add this line to save the note
                'updated_at' => now()
            ]
        );

        return redirect()->back()->with('success', 'Payment rejected successfully');
    }

    public function viewProof($id)
    {
        $transaction = Transaction::findOrFail($id);

        if (!$transaction->attachment) {
            abort(404);
        }

        // Decode base64 dan tampilkan sebagai gambar
        $imageData = base64_decode($transaction->attachment);

        return response($imageData)
            ->header('Content-Type', $this->getImageMimeType($transaction->attachment));
    }

    private function getImageMimeType($base64)
    {
        // Deteksi tipe MIME dari data base64
        $signature = substr($base64, 0, 20);

        if (strpos($signature, 'data:image/jpeg') === 0) {
            return 'image/jpeg';
        } elseif (strpos($signature, 'data:image/png') === 0) {
            return 'image/png';
        } elseif (strpos($signature, 'data:image/gif') === 0) {
            return 'image/gif';
        }

        // Default ke JPEG jika tidak bisa dideteksi
        return 'image/jpeg';
    }
}
