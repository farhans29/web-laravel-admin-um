<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Property;
use App\Exports\PaymentReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get properties for filter based on user access
        if ($user->isSuperAdmin()) {
            $properties = Property::where('status', 1)
                ->orderBy('name')
                ->get();
        } else {
            $properties = Property::where('status', 1)
                ->where('idrec', $user->property_id)
                ->orderBy('name')
                ->get();
        }

        // Set default date range (current month)
        $defaultStartDate = now()->startOfMonth()->format('Y-m-d');
        $defaultEndDate = now()->endOfMonth()->format('Y-m-d');

        // Get filter values
        $startDate = $request->filled('start_date') ? $request->start_date : $defaultStartDate;
        $endDate = $request->filled('end_date') ? $request->end_date : $defaultEndDate;

        // Set property_id based on user access
        if ($user->isSuperAdmin()) {
            $propertyId = $request->input('property_id');
        } else {
            $propertyId = $user->property_id;
        }

        return view('pages.reports.payment-report.index', compact(
            'properties',
            'startDate',
            'endDate',
            'propertyId'
        ));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = Transaction::with([
                'payment',
                'property',
                'room',
                'booking.refund'
            ])
            ->whereHas('payment')
            ->where('transaction_status', 'paid')
            ->orderByDesc('paid_at');

        // Date range filter based on paid_at date
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $query->whereBetween('paid_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        }

        // Property filter based on user access
        if ($user->isSuperAdmin()) {
            if ($request->filled('property_id')) {
                $query->where('property_id', $request->property_id);
            }
        } else {
            // Non-super admin: automatically filter by their property
            if ($user->property_id) {
                $query->where('property_id', $user->property_id);
            }
        }

        // Search filter (order_id, transaction_code, user_name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('transaction_code', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate($request->input('per_page', 15));

        // Transform data for display (18 columns)
        $data = $payments->map(function ($transaction, $index) use ($payments) {
            $payment = $transaction->payment;

            // Detect refund
            $isRefund = false;
            $refundInfo = null;
            if ($transaction->booking && $transaction->booking->refund) {
                $isRefund = true;
                $refundInfo = $transaction->booking->refund;
            }

            $offset = ($payments->currentPage() - 1) * $payments->perPage();

            return [
                'no' => $offset + $index + 1,
                'payment_date' => $transaction->paid_at ? Carbon::parse($transaction->paid_at)->format('d M Y H:i') : '-',
                'order_id' => $transaction->order_id,
                'transaction_code' => $transaction->transaction_code ?? '-',
                'property_name' => $transaction->property_name ?? '-',
                'room_number' => $transaction->room ? $transaction->room->name : '-',
                'room_name' => $transaction->room ? $transaction->room->name : '-',
                'tenant_name' => $transaction->user_name ?? '-',
                'mobile_number' => $transaction->user_phone_number ?? '-',
                'email' => $transaction->user_email ?? '-',
                'check_in' => $transaction->check_in ? Carbon::parse($transaction->check_in)->format('d M Y') : '-',
                'check_out' => $transaction->check_out ? Carbon::parse($transaction->check_out)->format('d M Y') : '-',
                'room_price' => 'Rp ' . number_format($transaction->room_price ?? 0, 0, ',', '.'),
                'service_fee' => 'Rp ' . number_format($transaction->service_fees ?? 0, 0, ',', '.'),
                'grand_total' => 'Rp ' . number_format($transaction->grandtotal_price ?? 0, 0, ',', '.'),
                'payment_status' => 'Paid',
                'verified_by' => $payment && $payment->verified_by ? $payment->verified_by : '-', // Direct field access
                'verified_at' => $payment && $payment->verified_at ? Carbon::parse($payment->verified_at)->format('d M Y H:i') : '-',
                'notes' => $this->formatNotes($transaction, $isRefund, $refundInfo),
                'is_refund' => $isRefund,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
            ]
        ]);
    }

    public function export(Request $request)
    {
        $user = Auth::user();

        // Set property_id based on user access
        $propertyId = $user->isSuperAdmin()
            ? $request->input('property_id')
            : $user->property_id;

        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'property_id' => $propertyId,
            'search' => $request->input('search'),
        ];

        $filename = 'payment-report-' . now()->format('Y-m-d-His') . '.xlsx';

        $exporter = new PaymentReportExport($filters);
        return $exporter->export($filename);
    }

    private function formatNotes($transaction, $isRefund, $refundInfo)
    {
        $notes = $transaction->notes ?? '';

        if ($isRefund && $refundInfo) {
            $refundDate = Carbon::parse($refundInfo->refund_date)->format('d M Y');
            $refundText = "REFUNDED on {$refundDate}";

            if ($refundInfo->reason) {
                $refundText .= " - Reason: {$refundInfo->reason}";
            }

            if ($refundInfo->amount) {
                $refundText .= " - Amount: Rp " . number_format($refundInfo->amount, 0, ',', '.');
            }

            $notes = $notes ? $notes . " | " . $refundText : $refundText;
        }

        return $notes;
    }
}
