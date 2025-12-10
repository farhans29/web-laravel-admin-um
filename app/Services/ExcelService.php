<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelService
{
    protected Spreadsheet $spreadsheet;
    protected $activeSheet;
    protected int $currentRow = 1;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->activeSheet = $this->spreadsheet->getActiveSheet();
    }

    /**
     * Set sheet title
     */
    public function setTitle(string $title): self
    {
        $this->activeSheet->setTitle($title);
        return $this;
    }

    /**
     * Add header row with styling
     */
    public function addHeader(array $headers): self
    {
        $column = 'A';
        foreach ($headers as $header) {
            $cellCoordinate = $column . $this->currentRow;
            $this->activeSheet->setCellValue($cellCoordinate, $header);

            // Style header
            $this->activeSheet->getStyle($cellCoordinate)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

            // Auto width
            $this->activeSheet->getColumnDimension($column)->setAutoSize(true);

            $column++;
        }

        $this->currentRow++;
        return $this;
    }

    /**
     * Add data rows
     */
    public function addRows(array $data): self
    {
        foreach ($data as $row) {
            $column = 'A';
            foreach ($row as $value) {
                $cellCoordinate = $column . $this->currentRow;
                $this->activeSheet->setCellValue($cellCoordinate, $value);

                // Style data cell
                $this->activeSheet->getStyle($cellCoordinate)->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);

                $column++;
            }
            $this->currentRow++;
        }

        return $this;
    }

    /**
     * Add single row
     */
    public function addRow(array $row): self
    {
        return $this->addRows([$row]);
    }

    /**
     * Set column width
     */
    public function setColumnWidth(string $column, float $width): self
    {
        $this->activeSheet->getColumnDimension($column)->setWidth($width);
        return $this;
    }

    /**
     * Freeze first row
     */
    public function freezeFirstRow(): self
    {
        $this->activeSheet->freezePane('A2');
        return $this;
    }

    /**
     * Apply auto filter
     */
    public function autoFilter(?string $range = null): self
    {
        if ($range === null) {
            $highestColumn = $this->activeSheet->getHighestColumn();
            $highestRow = $this->activeSheet->getHighestRow();
            $range = 'A1:' . $highestColumn . $highestRow;
        }

        $this->activeSheet->setAutoFilter($range);
        return $this;
    }

    /**
     * Download as Excel file
     */
    public function download(string $filename): StreamedResponse
    {
        // Ensure filename has .xlsx extension
        if (!str_ends_with($filename, '.xlsx')) {
            $filename .= '.xlsx';
        }

        return new StreamedResponse(
            function () {
                $writer = new Xlsx($this->spreadsheet);
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }

    /**
     * Save to file
     */
    public function save(string $path): bool
    {
        try {
            $writer = new Xlsx($this->spreadsheet);
            $writer->save($path);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get spreadsheet instance for advanced usage
     */
    public function getSpreadsheet(): Spreadsheet
    {
        return $this->spreadsheet;
    }

    /**
     * Get active sheet for advanced usage
     */
    public function getActiveSheet()
    {
        return $this->activeSheet;
    }

    /**
     * Create new instance (static factory)
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Quick export from array
     */
    public static function export(array $data, array $headers, string $filename, string $title = 'Sheet1')
    {
        $excel = self::create();

        $excel->setTitle($title)
            ->addHeader($headers)
            ->addRows($data)
            ->freezeFirstRow();

        return $excel->download($filename);
    }
}
