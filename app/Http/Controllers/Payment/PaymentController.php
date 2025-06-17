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
            ->whereIn('transaction_status', ['pending', 'waiting_payment', 'completed'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        return view('pages.payment.pay.index', compact('transactions'));
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Update transaction
        $transaction->update([
            'transaction_status' => 'completed',
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
                'payment_status' => 'completed',
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
            'transaction_status' => 'failed',
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
