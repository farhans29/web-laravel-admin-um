<?php

namespace App\Exports;

use App\Models\Booking;
use App\Models\Property;
use App\Services\ExcelService;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BookingReportExportNew
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function export(string $filename)
    {
        // Get data
        $bookings = $this->getBookings();
        $totalRevenue = $bookings->sum(function ($booking) {
            return $booking->transaction && $booking->transaction->grandtotal_price
                ? $booking->transaction->grandtotal_price
                : 0;
        });

        // Create Excel
        $excel = new ExcelService();
        $sheet = $excel->getActiveSheet();

        // Insert title rows
        $sheet->insertNewRowBefore(1, 6);

        // Title
        $sheet->setCellValue('A1', 'BOOKING REPORT');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1F2937']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Generated Date
        $sheet->setCellValue('A2', 'Generated: ' . now()->format('d M Y, H:i'));
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 10, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Filters Section
        $filterRow = 4;
        $sheet->setCellValue('A' . $filterRow, 'FILTERS APPLIED:');
        $sheet->getStyle('A' . $filterRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
        ]);

        $filterRow++;
        $filterTexts = $this->getFilterTexts();

        if (!empty($filterTexts)) {
            foreach ($filterTexts as $filterText) {
                $sheet->setCellValue('A' . $filterRow, $filterText);
                $sheet->getStyle('A' . $filterRow)->applyFromArray([
                    'font' => ['size' => 10],
                ]);
                $filterRow++;
            }
        } else {
            $sheet->setCellValue('A' . $filterRow, 'No filters applied - showing all bookings');
            $sheet->getStyle('A' . $filterRow)->applyFromArray([
                'font' => ['size' => 10, 'italic' => true, 'color' => ['rgb' => '6B7280']],
            ]);
        }

        // Headers
        $headers = [
            'Booking Date',
            'Booking Number',
            'Name',
            'Property',
            'Address',
            'Room',
            'Stay Period',
            'Payment',
            'Payment Type',
            'Total Revenue',
            'Status',
            'Notes'
        ];

        $excel->addHeader($headers);

        // Column widths
        $columnWidths = [
            'A' => 15,  // Booking Date
            'B' => 20,  // Booking Number
            'C' => 20,  // Name
            'D' => 25,  // Property
            'E' => 30,  // Address
            'F' => 20,  // Room
            'G' => 30,  // Stay Period
            'H' => 20,  // Payment
            'I' => 15,  // Payment Type
            'J' => 18,  // Total Revenue
            'K' => 20,  // Status
            'L' => 40,  // Notes
        ];

        foreach ($columnWidths as $col => $width) {
            $excel->setColumnWidth($col, $width);
        }

        // Style header row (row 7)
        $headerRow = 7;
        $sheet->getStyle('A' . $headerRow . ':L' . $headerRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '3730A3']
                ]
            ]
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(25);

        // Add data
        $dataStartRow = 8;
        $currentRow = $dataStartRow;

        foreach ($bookings as $booking) {
            $row = $this->mapBooking($booking);
            $excel->addRow($row);

            // Format as currency for revenue column
            $sheet->getStyle('J' . $currentRow)->getNumberFormat()->setFormatCode('#,##0');
            $currentRow++;
        }

        $dataEndRow = $currentRow - 1;

        // Style data rows
        if ($bookings->count() > 0) {
            $sheet->getStyle('A' . $dataStartRow . ':L' . $dataEndRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB']
                    ]
                ]
            ]);

            // Alternate row colors
            for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                if (($row - $dataStartRow) % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F9FAFB']
                        ]
                    ]);
                }
            }
        }

        // Add summary at the bottom
        $summaryRow = $dataEndRow + 2;

        // Total Revenue Row
        $sheet->setCellValue('A' . $summaryRow, 'TOTAL REVENUE:');
        $sheet->mergeCells('A' . $summaryRow . ':I' . $summaryRow);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1F2937']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);

        // Total Revenue Value
        $sheet->setCellValue('J' . $summaryRow, $totalRevenue);
        $sheet->getStyle('J' . $summaryRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '059669']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D1FAE5']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '059669']
                ]
            ]
        ]);
        $sheet->getStyle('J' . $summaryRow)->getNumberFormat()->setFormatCode('#,##0');

        // Total Records Row
        $recordsRow = $summaryRow + 1;
        $sheet->setCellValue('A' . $recordsRow, 'Total Records: ' . $bookings->count());
        $sheet->mergeCells('A' . $recordsRow . ':L' . $recordsRow);
        $sheet->getStyle('A' . $recordsRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'italic' => true, 'color' => ['rgb' => '6B7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);

        // Freeze panes
        $sheet->freezePane('A8');

        // Download
        return $excel->download($filename);
    }

    private function getBookings()
    {
        $query = Booking::with(['room', 'property', 'transaction', 'payment'])
            ->orderByDesc('created_at');

        // Apply filters
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $startDate = $this->filters['start_date'];
            $endDate = $this->filters['end_date'];

            $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            });
        }

        if (!empty($this->filters['status'])) {
            switch ($this->filters['status']) {
                case 'pending':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'pending');
                    });
                    break;
                case 'waiting':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'waiting');
                    });
                    break;
                case 'waiting-check-in':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'paid');
                    })->whereNull('check_in_at')->whereNull('check_out_at');
                    break;
                case 'checked-in':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'paid');
                    })->whereNotNull('check_in_at')->whereNull('check_out_at');
                    break;
                case 'checked-out':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'paid');
                    })->whereNotNull('check_in_at')->whereNotNull('check_out_at');
                    break;
                case 'canceled':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'canceled');
                    });
                    break;
                case 'expired':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'expired');
                    });
                    break;
            }
        }

        if (!empty($this->filters['property_id'])) {
            $query->where('property_id', $this->filters['property_id']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhereHas('room', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('property', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('location', 'like', "%{$search}%");
                    });
            });
        }

        return $query->get();
    }

    private function mapBooking($booking): array
    {
        $transaction = $booking->transaction;
        $payment = $booking->payment;

        // Combine payment date and time
        $paymentDatetime = '-';
        if ($payment && $payment->created_at) {
            $paymentDatetime = Carbon::parse($payment->created_at)->format('d M Y, H:i');
        } elseif ($transaction && $transaction->paid_at) {
            $paymentDatetime = Carbon::parse($transaction->paid_at)->format('d M Y, H:i');
        }

        // Payment type
        $paymentType = '-';
        if ($transaction && $transaction->transaction_type) {
            $paymentType = ucfirst($transaction->transaction_type);
        }

        // Total revenue
        $totalRevenue = 0;
        if ($transaction && $transaction->grandtotal_price) {
            $totalRevenue = $transaction->grandtotal_price;
        }

        // Stay period
        $stayPeriod = '-';
        if ($transaction && $transaction->check_in && $transaction->check_out) {
            $checkIn = Carbon::parse($transaction->check_in)->format('d M Y');
            $checkOut = Carbon::parse($transaction->check_out)->format('d M Y');
            $stayPeriod = $checkIn . ' to ' . $checkOut;
        }

        return [
            $transaction ? Carbon::parse($transaction->check_in)->format('d M Y') : '-',
            $booking->order_id,
            $booking->user_name ?? '-',
            $booking->property ? $booking->property->name : '-',
            $booking->property ? ($booking->property->address ?? '-') : '-',
            $booking->room ? $booking->room->name : '-',
            $stayPeriod,
            $paymentDatetime,
            $paymentType,
            $totalRevenue,
            $booking->status ?? 'Unknown',
            $transaction ? $transaction->notes : '',
        ];
    }

    private function getFilterTexts(): array
    {
        $filters = [];

        // Date Range
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $startDate = Carbon::parse($this->filters['start_date'])->format('d M Y');
            $endDate = Carbon::parse($this->filters['end_date'])->format('d M Y');
            $filters[] = '• Booking Date: ' . $startDate . ' to ' . $endDate;
        }

        // Status
        if (!empty($this->filters['status'])) {
            $statusLabels = [
                'pending' => 'Waiting For Payment',
                'waiting' => 'Waiting Confirmation',
                'waiting-check-in' => 'Waiting For Check-In',
                'checked-in' => 'Checked-In',
                'checked-out' => 'Checked-Out',
                'canceled' => 'Canceled',
                'expired' => 'Expired'
            ];
            $statusLabel = $statusLabels[$this->filters['status']] ?? $this->filters['status'];
            $filters[] = '• Status: ' . $statusLabel;
        }

        // Property/Address
        if (!empty($this->filters['property_id'])) {
            $property = Property::find($this->filters['property_id']);
            if ($property) {
                $filters[] = '• Address: ' . $property->name;
            }
        }

        // Search
        if (!empty($this->filters['search'])) {
            $filters[] = '• Search: "' . $this->filters['search'] . '"';
        }

        return $filters;
    }
}
