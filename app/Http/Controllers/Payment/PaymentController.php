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
        $user = Auth::user();
        $perPage = $request->input('per_page', 8);

        $query = Payment::with([
            'booking',
            'user',
            'verifiedBy',
            'transaction' => function ($query) {
                $query->with(['property', 'room', 'user']);
            }
        ])->whereHas('transaction', function ($q) use ($user) {
            // Filter by property based on user access
            if (!$user->isSuperAdmin() && $user->property_id) {
                $q->where('property_id', $user->property_id);
            }
        })
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
        $user = Auth::user();
        $perPage = $request->input('per_page', 8);
        $search = $request->input('search');
        $status = $request->input('status', 'all');

        $query = Payment::with([
            'booking',
            'user',
            'verifiedBy',
            'transaction' => function ($query) {
                $query->with(['property', 'room', 'user']);
            }
        ])->whereHas('transaction', function ($q) use ($user) {
            // Filter by property based on user access
            if (!$user->isSuperAdmin() && $user->property_id) {
                $q->where('property_id', $user->property_id);
            }
        })
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

    public function approve(Request $request, $id)
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
                        'verified_by' => Auth::id(),
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
                    'verified_by' => Auth::id(),
                    'verified_at' => now(),
                    'payment_status' => 'paid',
                ]);

                // Update related transaction
                if ($payment->transaction) {
                    $payment->transaction->update([
                        'transaction_status' => 'paid',
                        'paid_at' => now()
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Pembayaran berhasil disetujui');
        } catch (\Exception $e) {
            Log::error('Payment approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui pembayaran. Error: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            // Find payment by idrec (primary key)
            $payment = Payment::findOrFail($id);

            // Update payment record
            $payment->update([
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'payment_status' => 'rejected',
                'notes' => $request->input('rejectNote'),
                'updated_at' => now()
            ]);

            // Update related transaction if exists
            if ($payment->transaction) {
                $payment->transaction->update([
                    'transaction_status' => 'rejected',
                    'paid_at' => now()
                ]);
            }

            return redirect()->back()->with('success', 'Pembayaran berhasil ditolak');
        } catch (\Exception $e) {
            Log::error('Payment rejection failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak pembayaran. Error: ' . $e->getMessage());
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
            'cancel_at' => Carbon::now(),
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

    public function updatePaymentDate(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            // Get check-in date
            $checkInDate = $payment->transaction?->check_in;

            if (!$checkInDate) {
                return redirect()->back()->with('error', 'Tanggal check-in tidak ditemukan');
            }

            $request->validate([
                'payment_date' => [
                    'required',
                    'date',
                    'before_or_equal:' . $checkInDate->format('Y-m-d H:i:s'),
                ],
            ], [
                'payment_date.required' => 'Tanggal pembayaran wajib diisi',
                'payment_date.date' => 'Format tanggal tidak valid',
                'payment_date.before_or_equal' => 'Tanggal pembayaran harus sebelum atau sama dengan tanggal check-in (' . $checkInDate->format('d M Y H:i') . ')',
            ]);

            $newPaymentDate = Carbon::parse($request->payment_date);

            // Update payment date
            if ($payment->transaction && $payment->transaction->paid_at) {
                $payment->transaction->update([
                    'paid_at' => $newPaymentDate
                ]);
            } elseif ($payment->verified_at) {
                $payment->update([
                    'verified_at' => $newPaymentDate
                ]);
            }

            return redirect()->back()->with('success', 'Tanggal pembayaran berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Update payment date failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui tanggal pembayaran. Error: ' . $e->getMessage());
        }
    }

    public function updateCheckInOut(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            // Get current check-in and check-out dates
            $currentCheckIn = $payment->transaction?->check_in;
            $currentCheckOut = $payment->transaction?->check_out;

            if (!$currentCheckIn) {
                return redirect()->back()->with('error', 'Tanggal check-in tidak ditemukan');
            }

            $validationRules = [
                'check_in' => [
                    'required',
                    'date',
                    'before_or_equal:' . $currentCheckIn->format('Y-m-d H:i:s'),
                ],
            ];

            $validationMessages = [
                'check_in.required' => 'Tanggal check-in wajib diisi',
                'check_in.date' => 'Format tanggal check-in tidak valid',
                'check_in.before_or_equal' => 'Tanggal check-in harus sebelum atau sama dengan tanggal check-in saat ini (' . $currentCheckIn->format('d M Y H:i') . ')',
                'check_out.date' => 'Format tanggal check-out tidak valid',
                'check_out.after' => 'Tanggal check-out harus setelah tanggal check-in',
            ];

            // Add check-out validation - allow free date selection for check-out
            $validationRules['check_out'] = [
                'nullable',
                'date',
                'after:check_in',
            ];

            $request->validate($validationRules, $validationMessages);

            $newCheckIn = Carbon::parse($request->check_in);
            $newCheckOut = $request->check_out ? Carbon::parse($request->check_out) : null;

            // Update check-in and check-out dates
            if ($payment->transaction) {
                $updateData = ['check_in' => $newCheckIn];

                if ($newCheckOut) {
                    $updateData['check_out'] = $newCheckOut;
                }

                $payment->transaction->update($updateData);
            }

            return redirect()->back()->with('success', 'Tanggal check-in/check-out berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Update check-in/out failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui tanggal check-in/check-out. Error: ' . $e->getMessage());
        }
    }
}
