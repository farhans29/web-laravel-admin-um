<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\Property;
use App\Services\ExcelService;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PaymentReportExport
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function export(string $filename)
    {
        // Get data
        $payments = $this->getPayments();
        $totalRevenue = $payments->sum(function ($transaction) {
            return $transaction->grandtotal_price ?? 0;
        });
        $totalRefunds = $payments->filter(function ($transaction) {
            return $transaction->booking && $transaction->booking->refund;
        })->count();

        // Create Excel
        $excel = new ExcelService();
        $sheet = $excel->getActiveSheet();

        // Add Main Title with custom styling
        $excel->addTitleSection('💰 LAPORAN KEUANGAN PEMBAYARAN', [
            'bgColor' => '059669', // Green
            'textColor' => 'FFFFFF',
            'fontSize' => 20,
            'height' => 40,
            'endColumn' => 'R', // 18 columns
        ]);

        // Add subtitle with generation info
        $excel->addInfoRow('Generated on: ' . now()->format('l, d F Y - H:i:s'), [
            'fontSize' => 10,
            'textColor' => '6B7280',
            'italic' => true,
            'align' => Alignment::HORIZONTAL_CENTER,
            'endColumn' => 'R',
        ]);

        $excel->addEmptyRow();

        // Add filter section
        $filterTexts = $this->getFilterTexts();
        $excel->addFilterSection($filterTexts);

        // Add separator
        $excel->addInfoRow('', []);

        // Headers with enhanced styling
        $headers = [
            'No.',
            'Payment Date',
            'Order ID',
            'Transaction Code',
            'Property Name',
            'Room Number',
            'Tenant Name',
            'Mobile Number',
            'Email',
            'Check In',
            'Check Out',
            'Room Price',
            'Service Fee',
            'Grand Total',
            'Payment Status',
            'Verification By',
            'Verification Date',
            'Notes'
        ];

        $excel->addHeader($headers);

        // Style header row with custom colors
        $actualHeaderRow = $excel->getCurrentRow() - 1;
        $sheet->getStyle('A' . $actualHeaderRow . ':R' . $actualHeaderRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'] // Green
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '047857']
                ]
            ]
        ]);
        $sheet->getRowDimension($actualHeaderRow)->setRowHeight(28);

        // Column widths
        $columnWidths = [
            'A' => 8,  // No.
            'B' => 18, // Payment Date
            'C' => 20, // Order ID
            'D' => 20, // Transaction Code
            'E' => 25, // Property Name
            'F' => 15, // Room Number
            'G' => 20, // Tenant Name
            'H' => 18, // Mobile Number
            'I' => 25, // Email
            'J' => 15, // Check In
            'K' => 15, // Check Out
            'L' => 15, // Room Price
            'M' => 12, // Admin Fee
            'N' => 15, // Grand Total
            'O' => 15, // Payment Status
            'P' => 20, // Verification By
            'Q' => 18, // Verification Date
            'R' => 40, // Notes
        ];

        foreach ($columnWidths as $col => $width) {
            $excel->setColumnWidth($col, $width);
        }

        // Add data rows with enhanced styling
        $dataStartRow = $excel->getCurrentRow();

        foreach ($payments as $index => $transaction) {
            $row = $this->mapPayment($transaction, $index + 1);
            $currentDataRow = $excel->getCurrentRow();

            $excel->addRow($row);

            // Highlight refunds with red background
            $isRefund = $transaction->booking && $transaction->booking->refund;
            if ($isRefund) {
                $sheet->getStyle('A' . $currentDataRow . ':R' . $currentDataRow)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEE2E2'] // Light red
                    ]
                ]);
            } else {
                // Add zebra striping for non-refund rows
                if ($index % 2 == 0) {
                    $sheet->getStyle('A' . $currentDataRow . ':R' . $currentDataRow)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F9FAFB']
                        ]
                    ]);
                }
            }

            // Format as currency for price columns
            $sheet->getStyle('L' . $currentDataRow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('M' . $currentDataRow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('N' . $currentDataRow)->getNumberFormat()->setFormatCode('Rp #,##0');
        }

        $dataEndRow = $excel->getCurrentRow() - 1;

        // Style data rows with borders
        if ($payments->count() > 0) {
            $sheet->getStyle('A' . $dataStartRow . ':R' . $dataEndRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D1D5DB']
                    ]
                ]
            ]);
        }

        // Add spacing before summary
        $excel->addEmptyRow(2);

        // Add summary section with enhanced design
        $excel->addInfoRow('💵 SUMMARY REPORT', [
            'bold' => true,
            'bgColor' => 'D1FAE5',
            'textColor' => '059669',
            'fontSize' => 12,
            'align' => Alignment::HORIZONTAL_CENTER,
            'endColumn' => 'R',
        ]);

        $excel->addEmptyRow();

        // Total Revenue using new method
        $excel->addSummaryRow('TOTAL REVENUE:', $totalRevenue, [
            'labelColumn' => 'A',
            'valueColumn' => 'N',
            'labelEndColumn' => 'M',
            'bgColor' => 'D1FAE5',
            'textColor' => '059669',
        ]);

        // Format revenue as currency
        $summaryRowNum = $excel->getCurrentRow() - 1;
        $sheet->getStyle('N' . $summaryRowNum)->getNumberFormat()->setFormatCode('Rp #,##0');

        // Total Records
        $excel->addInfoRow('Total Payments: ' . $payments->count() . ' | Refunds: ' . $totalRefunds, [
            'bold' => true,
            'fontSize' => 10,
            'textColor' => '6B7280',
            'align' => Alignment::HORIZONTAL_RIGHT,
            'endColumn' => 'R',
        ]);

        // Add footer
        $excel->addEmptyRow();
        $excel->addInfoRow('Report generated by Booking Management System', [
            'fontSize' => 9,
            'italic' => true,
            'textColor' => '9CA3AF',
            'align' => Alignment::HORIZONTAL_CENTER,
            'endColumn' => 'R',
        ]);

        // Freeze panes at header row
        $sheet->freezePane('A' . ($actualHeaderRow + 1));

        // Download
        return $excel->download($filename);
    }

    private function getPayments()
    {
        $query = Transaction::with([
                'payment',
                'property',
                'room',
                'booking.refund'
            ])
            ->whereHas('payment')
            ->where('transaction_status', 'paid')
            ->orderByDesc('paid_at');

        // Apply filters
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $startDate = $this->filters['start_date'];
            $endDate = $this->filters['end_date'];

            $query->whereBetween('paid_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        }

        if (!empty($this->filters['property_id'])) {
            $query->where('property_id', $this->filters['property_id']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('transaction_code', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    private function mapPayment($transaction, $no): array
    {
        $payment = $transaction->payment;

        // Detect refund
        $isRefund = $transaction->booking && $transaction->booking->refund;
        $refundSuffix = $isRefund ? ' [REFUND]' : '';

        // Format notes with refund info
        $notes = $transaction->notes ?? '';
        if ($isRefund && $transaction->booking->refund) {
            $refundInfo = $transaction->booking->refund;
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

        return [
            $no,
            $transaction->paid_at ? Carbon::parse($transaction->paid_at)->format('d M Y H:i') : '-',
            $transaction->order_id . $refundSuffix,
            $transaction->transaction_code ?? '-',
            $transaction->property_name ?? '-',
            $transaction->room ? $transaction->room->name : '-',
            $transaction->user_name ?? '-',
            $transaction->user_phone_number ?? '-',
            $transaction->user_email ?? '-',
            $transaction->check_in ? Carbon::parse($transaction->check_in)->format('d M Y') : '-',
            $transaction->check_out ? Carbon::parse($transaction->check_out)->format('d M Y') : '-',
            $transaction->room_price ?? 0,
            $transaction->service_fees ?? 0, // Fixed: service_fees (with 's')
            $transaction->grandtotal_price ?? 0,
            'Paid',
            $payment && $payment->verified_by ? $payment->verified_by : '-', // Direct field access
            $payment && $payment->verified_at ? Carbon::parse($payment->verified_at)->format('d M Y H:i') : '-',
            $notes,
        ];
    }

    private function getFilterTexts(): array
    {
        $filters = [];

        // Date Range
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $startDate = Carbon::parse($this->filters['start_date'])->format('d M Y');
            $endDate = Carbon::parse($this->filters['end_date'])->format('d M Y');
            $filters[] = '📅 Payment Period: ' . $startDate . ' - ' . $endDate;
        }

        // Property
        if (!empty($this->filters['property_id'])) {
            $property = Property::find($this->filters['property_id']);
            if ($property) {
                $filters[] = '🏢 Property: ' . $property->name;
            }
        }

        // Search
        if (!empty($this->filters['search'])) {
            $filters[] = '🔎 Search Keyword: "' . $this->filters['search'] . '"';
        }

        return $filters;
    }
}
