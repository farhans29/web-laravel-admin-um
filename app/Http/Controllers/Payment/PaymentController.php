<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = Payment::with(['booking', 'transaction', 'user'])
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
