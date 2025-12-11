<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Refund;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = Payment::with([
            'booking',
            'user',
            'transaction' => function ($query) {
                $query->with(['property', 'room', 'user'])
                    ->where('status', 1);
            }
        ])->whereHas('transaction') // Hanya ambil payment yang punya transaction
            ->orderBy('idrec', 'desc');

        $payments = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        return view('pages.payment.pay.index', [
            'payments' => $payments,
            'per_page' => $perPage,
        ]);
    }

    public function filter(Request $request)
    {
        $perPage = $request->input('per_page', 8);
        $search = $request->input('search');
        $status = $request->input('status', 'all');

        $query = Payment::with(['booking', 'transaction', 'user'])
            ->orderBy('idrec', 'desc');

        // Filter based on search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('transaction', function ($transactionQuery) use ($search) {
                        $transactionQuery->where('order_id', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter based on status
        if ($status !== 'all') {
            $query->whereHas('transaction', function ($q) use ($status) {
                $q->where('transaction_status', $status);
            });
        }

        // Date range filter if needed
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->input('start_date') . ' 00:00:00',
                $request->input('end_date') . ' 23:59:59'
            ]);
        }

        $payments = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.payment.pay.partials.pay_table', ['payments' => $payments])->render(),
            ]);
        }

        return view('pages.payment.pay.index', [
            'payments' => $payments,
            'per_page' => $perPage,
        ]);
    }

    public function approve($id)
    {
        try {
            // First try to find the payment record
            $payment = Payment::where('idrec', $id)->first();

            if (!$payment) {
                // Fallback to transaction if payment not found
                $transaction = Transaction::where('order_id', $id)->orWhere('id', $id)->firstOrFail();

                $payment = Payment::updateOrCreate(
                    ['order_id' => $transaction->order_id],
                    [
                        'property_id' => $transaction->property_id,
                        'room_id' => $transaction->room_id,
                        'user_id' => $transaction->user_id,
                        'grandtotal_price' => $transaction->grandtotal_price,
                        'verified_by' => Auth::id() ?? 1, // Fallback to admin ID 1 if not authenticated
                        'verified_at' => now(),
                        'payment_status' => 'paid',
                    ]
                );

                $transaction->update([
                    'transaction_status' => 'paid',
                    'paid_at' => now()
                ]);
            } else {
                // Update existing payment
                $payment->update([
                    'verified_by' => Auth::id() ?? 1,
                    'verified_at' => now(),
                    'payment_status' => 'paid',
                ]);

                // Update related transaction
                if ($payment->order_id) {
                    Transaction::where('order_id', $payment->order_id)->update([
                        'transaction_status' => 'paid',
                        'paid_at' => now()
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Payment approved successfully');
        } catch (\Exception $e) {
            Log::error('Payment approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment approval failed. Error: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            // Find payment by idrec (primary key)
            $payment = Payment::findOrFail($id);

            // Update payment record
            $payment->update([
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'payment_status' => 'rejected',
                'notes' => request('rejectNote'),
                'updated_at' => now()
            ]);

            // Update related transaction if exists
            if ($payment->transaction) {
                $payment->transaction->update([
                    'transaction_status' => 'rejected',
                    'paid_at' => now()
                ]);
            }

            return redirect()->back()->with('success', 'Payment rejected successfully');
        } catch (\Exception $e) {
            Log::error('Payment rejection failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment rejection failed. Error: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        // Validasi status transaksi
        if (!in_array($payment->transaction->transaction_status, ['paid', 'completed'])) {
            return redirect()->back()->with('error', 'Hanya booking dengan status terverifikasi yang dapat dibatalkan.');
        }

        // Tentukan alasan pembatalan
        $cancelReason = $request->cancelReason === 'other'
            ? $request->customCancelReason
            : $request->cancelReason;

        // Bersihkan nilai refundAmount dari format rupiah (misal: "1.000.000" → 1000000)
        $refundAmount = (int) str_replace(['Rp', '.', ' '], '', $request->refundAmount);

        // Simpan data refund ke tabel t_refund
        Refund::create([
            'id_booking'    => $payment->order_id,
            'status'        => 'pending',
            'reason'        => $cancelReason,
            'amount'        => $refundAmount,
            'img'           => null,
            'image_caption' => null,
            'image_path'    => null,
            'refund_date'   => Carbon::now(),
        ]);

        // Update status transaksi & pembayaran
        $payment->transaction->update([
            'transaction_status' => 'cancelled',
        ]);

        // Update related booking status to 0 (cancelled)
        if ($payment->booking) {
            $payment->booking->update([
                'status' => '0',
                'reason' => $cancelReason,
            ]);
        } else {
            Booking::where('order_id', $payment->order_id)->update([
                'status' => '0',
                'reason' => $cancelReason,
            ]);
        }

        $payment->update([
            'payment_status' => 'refunded',
        ]);

        // Kirim notifikasi (opsional)
        if ($request->has('sendNotification')) {
            // logika kirim notifikasi ke pelanggan (jika diperlukan)
        }

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan dan data refund telah disimpan.');
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
