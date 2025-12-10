# Custom Excel Library untuk Laravel

Library Excel kustom yang kompatibel dengan PHP 8.2+ dan Laravel 11, dibuat menggunakan PhpSpreadsheet sebagai alternatif dari maatwebsite/excel.

## Instalasi

### 1. Install Package

Jalankan composer install untuk menginstall dependencies:

```bash
composer install
```

Atau install secara manual:

```bash
composer require phpoffice/phpspreadsheet:^2.0
```

### 2. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload -o
```

## Struktur Library

Library ini terdiri dari beberapa komponen:

### 1. ExcelService (Core Service)
**Location:** `app/Services/ExcelService.php`

Service utama untuk membuat dan mengexport file Excel.

### 2. Excel Facade
**Location:** `app/Facades/Excel.php`

Facade untuk mengakses ExcelService dengan mudah.

### 3. ExcelServiceProvider
**Location:** `app/Providers/ExcelServiceProvider.php`

Service provider yang sudah terdaftar di `config/app.php`.

### 4. Export Classes
- `app/Exports/BookingReportExport.php` - Menggunakan maatwebsite/excel (lama)
- `app/Exports/BookingReportExportNew.php` - Menggunakan custom library (baru)

## Cara Menggunakan

### Metode 1: Menggunakan ExcelService Langsung

```php
use App\Services\ExcelService;

$excel = new ExcelService();

// Set title
$excel->setTitle('Laporan Booking');

// Add header
$excel->addHeader(['No', 'Nama', 'Email', 'Total']);

// Add data rows
$excel->addRows([
    [1, 'John Doe', 'john@example.com', 150000],
    [2, 'Jane Smith', 'jane@example.com', 200000],
]);

// Download
return $excel->download('laporan-booking.xlsx');
```

### Metode 2: Menggunakan Facade

```php
use App\Facades\Excel;

return Excel::export(
    data: [
        [1, 'John Doe', 'john@example.com', 150000],
        [2, 'Jane Smith', 'jane@example.com', 200000],
    ],
    headers: ['No', 'Nama', 'Email', 'Total'],
    filename: 'laporan.xlsx',
    title: 'Laporan Data'
);
```

### Metode 3: Menggunakan Export Class (Recommended)

Buat class export baru di `app/Exports/`:

```php
<?php

namespace App\Exports;

use App\Services\ExcelService;

class UserReportExport
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function export(string $filename)
    {
        $excel = new ExcelService();

        // Set title
        $excel->setTitle('User Report');

        // Add headers
        $excel->addHeader(['ID', 'Name', 'Email', 'Created At']);

        // Add data
        foreach ($this->data as $user) {
            $excel->addRow([
                $user->id,
                $user->name,
                $user->email,
                $user->created_at->format('d M Y'),
            ]);
        }

        // Freeze header
        $excel->freezeFirstRow();

        // Download
        return $excel->download($filename);
    }
}
```

Gunakan di Controller:

```php
public function export()
{
    $users = User::all();
    $exporter = new UserReportExport($users);
    return $exporter->export('users-report.xlsx');
}
```

## Fitur ExcelService

### 1. Set Title Sheet
```php
$excel->setTitle('Nama Sheet');
```

### 2. Add Header dengan Styling
```php
$excel->addHeader(['Kolom 1', 'Kolom 2', 'Kolom 3']);
```

### 3. Add Multiple Rows
```php
$excel->addRows([
    ['data1', 'data2', 'data3'],
    ['data4', 'data5', 'data6'],
]);
```

### 4. Add Single Row
```php
$excel->addRow(['data1', 'data2', 'data3']);
```

### 5. Set Column Width
```php
$excel->setColumnWidth('A', 20);
$excel->setColumnWidth('B', 30);
```

### 6. Freeze First Row (Header)
```php
$excel->freezeFirstRow();
```

### 7. Apply Auto Filter
```php
$excel->autoFilter(); // Auto detect range
$excel->autoFilter('A1:E10'); // Custom range
```

### 8. Download File
```php
return $excel->download('filename.xlsx');
```

### 9. Save to File
```php
$excel->save(storage_path('app/reports/filename.xlsx'));
```

### 10. Access PhpSpreadsheet Directly
Untuk fitur advanced:
```php
$spreadsheet = $excel->getSpreadsheet();
$sheet = $excel->getActiveSheet();

// Contoh: Merge cells
$sheet->mergeCells('A1:C1');

// Contoh: Set formula
$sheet->setCellValue('D5', '=SUM(D2:D4)');
```

## Contoh Implementasi di Controller

### BookingReportController

File sudah diupdate dengan fallback mechanism:

```php
public function export(Request $request)
{
    $filters = [
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
        'status' => $request->input('status'),
        'property_id' => $request->input('property_id'),
        'search' => $request->input('search'),
    ];

    $filename = 'booking-report-' . now()->format('Y-m-d-His') . '.xlsx';

    // Try using custom Excel library first
    try {
        $exporter = new BookingReportExportNew($filters);
        return $exporter->export($filename);
    } catch (\Exception $e) {
        // Fallback to maatwebsite/excel if custom library fails
        if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
            return MaatwebsiteExcel::download(new BookingReportExport($filters), $filename);
        }
        throw $e;
    }
}
```

## Styling Advanced

### Custom Cell Styling

```php
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$excel = new ExcelService();
$sheet = $excel->getActiveSheet();

// Set background color
$sheet->getStyle('A1')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FF0000']
    ]
]);

// Set text alignment
$sheet->getStyle('A1')->applyFromArray([
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ]
]);

// Set borders
$sheet->getStyle('A1:D10')->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);
```

## Troubleshooting

### Error: Class 'PhpOffice\PhpSpreadsheet\Spreadsheet' not found

**Solusi:**
```bash
composer require phpoffice/phpspreadsheet:^2.0
composer dump-autoload -o
```

### Error: Target class [excel] does not exist

**Solusi:**
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload -o
```

Pastikan ExcelServiceProvider sudah terdaftar di `config/app.php`:
```php
'providers' => [
    // ...
    App\Providers\ExcelServiceProvider::class,
]
```

### Memory Limit Error untuk File Besar

Untuk data dalam jumlah besar, gunakan chunking:

```php
$excel = new ExcelService();
$excel->addHeader(['Col1', 'Col2', 'Col3']);

// Process in chunks
User::chunk(1000, function ($users) use ($excel) {
    foreach ($users as $user) {
        $excel->addRow([$user->id, $user->name, $user->email]);
    }
});

return $excel->download('large-report.xlsx');
```

## Perbandingan dengan Maatwebsite/Excel

| Fitur | Custom Library | Maatwebsite/Excel |
|-------|---------------|-------------------|
| Kompatibilitas Laravel 11 | ✅ Penuh | ⚠️ Tergantung versi |
| PHP 8.2+ Support | ✅ Ya | ⚠️ Tergantung versi |
| Ukuran | Lebih ringan | Lebih besar |
| Mudah Custom | ✅ Ya | ⚠️ Perlu extends |
| Auto Styling | ✅ Ya | ❌ Manual |
| Dependency | Minimal | Banyak |
| Deploy ke cPanel | ✅ Mudah | ⚠️ Sering error |

## Deploy ke Production (cPanel)

### 1. Upload Files
Upload semua file ke server

### 2. Install Dependencies
```bash
cd /path/to/project
composer install --optimize-autoloader --no-dev
```

### 3. Clear & Optimize
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload -o
php artisan config:cache
php artisan route:cache
```

### 4. Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Support & Dokumentasi

- PhpSpreadsheet Docs: https://phpspreadsheet.readthedocs.io/
- Laravel Docs: https://laravel.com/docs

## Changelog

### Version 1.0 (2025-12-10)
- Initial release
- Support PHP 8.2+
- Support Laravel 11
- Basic Excel export features
- Custom styling support
- Fallback mechanism ke maatwebsite/excel
