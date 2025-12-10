# 🎨 Excel Design Enhancement Guide

Panduan lengkap untuk membuat Excel export dengan desain yang menarik dan profesional.

## ✨ Fitur Baru ExcelService

### 1. **addTitleSection** - Judul Utama
Menambahkan judul besar dengan background berwarna.

```php
$excel->addTitleSection('📊 LAPORAN BOOKING KAMAR', [
    'bgColor' => '1E3A8A',      // Dark blue background
    'textColor' => 'FFFFFF',     // White text
    'fontSize' => 20,            // Font size
    'height' => 40,              // Row height
    'startColumn' => 'A',        // Optional: start column
    'endColumn' => 'L',          // Optional: end column
]);
```

**Output:** Judul besar di tengah dengan background biru tua

---

### 2. **addInfoRow** - Informasi/Subtitle
Menambahkan baris informasi dengan styling custom.

```php
$excel->addInfoRow('Generated on: 10 December 2025', [
    'fontSize' => 10,
    'textColor' => '6B7280',     // Gray text
    'italic' => true,
    'bold' => false,
    'align' => Alignment::HORIZONTAL_CENTER,
    'bgColor' => null,           // Optional: background color
]);
```

**Use Cases:**
- Tanggal generate report
- Subtitle
- Catatan kaki
- Informasi tambahan

---

### 3. **addFilterSection** - Informasi Filter
Menambahkan section khusus untuk menampilkan filter yang diaplikasikan.

```php
$filters = [
    '📅 Booking Period: 01 Dec 2025 - 31 Dec 2025',
    '📊 Status: ✅ Checked-In',
    '🏢 Property: Gedung A',
];

$excel->addFilterSection($filters);
```

**Output:**
```
🔍 APPLIED FILTERS
   📅 Booking Period: 01 Dec 2025 - 31 Dec 2025
   📊 Status: ✅ Checked-In
   🏢 Property: Gedung A
```

Jika tidak ada filter:
```
📋 No filters applied - showing all data
```

---

### 4. **addCompanyInfo** - Info Organisasi
Menambahkan informasi perusahaan/organisasi di header.

```php
$excel->addCompanyInfo('UNIVERSITAS MUHAMMADIYAH', [
    'Sistem Manajemen Booking Kamar',
    'Jl. Example No. 123, Kota',
    'Tel: (021) 1234567'
]);
```

**Output:**
```
UNIVERSITAS MUHAMMADIYAH (bold, center, 14pt)
Sistem Manajemen Booking Kamar (center, 10pt, gray)
Jl. Example No. 123, Kota (center, 10pt, gray)
Tel: (021) 1234567 (center, 10pt, gray)
```

---

### 5. **addSummaryRow** - Ringkasan dengan Highlight
Menambahkan baris summary dengan styling khusus.

```php
$excel->addSummaryRow('TOTAL REVENUE:', 15000000, [
    'labelColumn' => 'A',
    'valueColumn' => 'J',
    'labelEndColumn' => 'I',
    'bgColor' => 'D1FAE5',       // Light green background
    'textColor' => '059669',     // Green text
    'borderColor' => '10B981',   // Green border
]);
```

**Output:** Label rata kanan dari A-I, value di J dengan background hijau muda dan border hijau

---

### 6. **addEmptyRow** - Spacing
Menambahkan baris kosong untuk spacing.

```php
$excel->addEmptyRow();      // 1 baris kosong
$excel->addEmptyRow(3);     // 3 baris kosong
```

---

## 🎯 Contoh Implementasi Lengkap

### Booking Report dengan Desain Premium

```php
<?php

namespace App\Exports;

use App\Services\ExcelService;
use Carbon\Carbon;

class BookingReportPremium
{
    protected $filters;
    protected $data;

    public function export(string $filename)
    {
        $excel = new ExcelService();
        $sheet = $excel->getActiveSheet();

        // 1. HEADER - Company Info
        $excel->addCompanyInfo('UNIVERSITAS MUHAMMADIYAH', [
            'Sistem Manajemen Booking Kamar',
            'Jl. Kampus No. 1, Yogyakarta',
        ]);

        // 2. TITLE - Main Title
        $excel->addTitleSection('📊 LAPORAN BOOKING KAMAR', [
            'bgColor' => '1E3A8A',
            'textColor' => 'FFFFFF',
            'fontSize' => 20,
            'height' => 40,
        ]);

        // 3. SUBTITLE - Generation Info
        $excel->addInfoRow('Generated on: ' . now()->format('l, d F Y - H:i:s'), [
            'fontSize' => 10,
            'textColor' => '6B7280',
            'italic' => true,
            'align' => Alignment::HORIZONTAL_CENTER,
        ]);

        $excel->addEmptyRow();

        // 4. FILTERS - Applied Filters
        $filterTexts = $this->getFilterTexts();
        $excel->addFilterSection($filterTexts);

        $excel->addInfoRow('', []); // Separator

        // 5. TABLE HEADER
        $headers = [
            'Date', 'Booking No', 'Customer', 'Property',
            'Room', 'Check In', 'Check Out', 'Amount', 'Status'
        ];
        $excel->addHeader($headers);

        // Custom header styling
        $headerRow = $excel->getCurrentRow() - 1;
        $sheet->getStyle('A' . $headerRow . ':I' . $headerRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3730A3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1E3A8A']]],
        ]);

        // 6. DATA ROWS with Zebra Striping
        foreach ($this->data as $index => $row) {
            $currentRow = $excel->getCurrentRow();
            $excel->addRow($row);

            // Zebra striping
            if ($index % 2 == 0) {
                $sheet->getStyle('A' . $currentRow . ':I' . $currentRow)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9FAFB']],
                ]);
            }
        }

        // 7. SUMMARY SECTION
        $excel->addEmptyRow(2);

        $excel->addInfoRow('📈 SUMMARY REPORT', [
            'bold' => true,
            'bgColor' => 'E0E7FF',
            'textColor' => '3730A3',
            'fontSize' => 12,
            'align' => Alignment::HORIZONTAL_CENTER,
        ]);

        $excel->addEmptyRow();

        // Total Revenue
        $totalRevenue = array_sum(array_column($this->data, 7)); // Column 8 (index 7)
        $excel->addSummaryRow('TOTAL REVENUE:', $totalRevenue, [
            'valueColumn' => 'H',
            'labelEndColumn' => 'G',
            'bgColor' => 'D1FAE5',
            'textColor' => '059669',
            'borderColor' => '10B981',
        ]);

        // Total Records
        $excel->addInfoRow('Total Records: ' . count($this->data) . ' bookings', [
            'bold' => true,
            'fontSize' => 10,
            'textColor' => '6B7280',
            'align' => Alignment::HORIZONTAL_RIGHT,
        ]);

        // 8. FOOTER
        $excel->addEmptyRow();
        $excel->addInfoRow('Report generated by Booking Management System', [
            'fontSize' => 9,
            'italic' => true,
            'textColor' => '9CA3AF',
            'align' => Alignment::HORIZONTAL_CENTER,
        ]);

        // 9. FREEZE PANES
        $sheet->freezePane('A' . ($headerRow + 1));

        return $excel->download($filename);
    }

    private function getFilterTexts(): array
    {
        $filters = [];

        if (!empty($this->filters['date_range'])) {
            $filters[] = '📅 Period: ' . $this->filters['date_range'];
        }
        if (!empty($this->filters['status'])) {
            $filters[] = '📊 Status: ' . $this->filters['status'];
        }
        if (!empty($this->filters['property'])) {
            $filters[] = '🏢 Property: ' . $this->filters['property'];
        }

        return $filters;
    }
}
```

---

## 🎨 Color Palette Reference

### Background Colors
```php
'1E3A8A'  // Dark Blue - For main title
'3730A3'  // Indigo - For table headers
'E0E7FF'  // Light Indigo - For section headers
'F3F4F6'  // Light Gray - For filter section
'D1FAE5'  // Light Green - For positive summaries
'FEE2E2'  // Light Red - For negative summaries
'F9FAFB'  // Very Light Gray - For zebra striping
```

### Text Colors
```php
'FFFFFF'  // White - For dark backgrounds
'1F2937'  // Dark Gray - For main text
'3730A3'  // Indigo - For headers
'059669'  // Green - For positive numbers
'DC2626'  // Red - For negative numbers
'6B7280'  // Medium Gray - For subtitles
'9CA3AF'  // Light Gray - For footers
```

---

## 📊 Best Practices

### 1. **Hierarchy yang Jelas**
```php
// Main Title (Largest)
$excel->addTitleSection('TITLE', ['fontSize' => 20]);

// Section Headers (Medium)
$excel->addInfoRow('SECTION', ['fontSize' => 12, 'bold' => true]);

// Data Headers (Table)
$excel->addHeader($headers); // Auto styled

// Regular Text (Smallest)
$excel->addInfoRow('Info', ['fontSize' => 10]);
```

### 2. **Konsistensi Warna**
- Gunakan palet warna yang konsisten
- Background gelap = text putih
- Background terang = text gelap
- Hijau untuk angka positif/revenue
- Merah untuk angka negatif/loss

### 3. **Spacing yang Tepat**
```php
// After title
$excel->addEmptyRow();

// Before summary
$excel->addEmptyRow(2);

// After filters
$excel->addInfoRow('', []); // Separator line
```

### 4. **Icons untuk Visual**
Gunakan emoji untuk membuat lebih menarik:
```php
'📊' // Charts/Reports
'📅' // Calendar/Dates
'🏢' // Building/Property
'🔍' // Search
'✅' // Success/Completed
'❌' // Failed/Canceled
'⏳' // Pending
'💰' // Money/Revenue
'📈' // Trending Up
```

---

## 🚀 Quick Templates

### Template 1: Simple Report
```php
$excel->addTitleSection('Report Title');
$excel->addInfoRow('Generated: ' . now()->format('d M Y'));
$excel->addEmptyRow();
$excel->addHeader(['Col1', 'Col2', 'Col3']);
$excel->addRows($data);
```

### Template 2: Report with Filters
```php
$excel->addTitleSection('Report Title');
$excel->addFilterSection($filters);
$excel->addEmptyRow();
$excel->addHeader($headers);
$excel->addRows($data);
```

### Template 3: Full Featured Report
```php
$excel->addCompanyInfo('Company Name', ['Subtitle/Tagline']);
$excel->addTitleSection('Report Title');
$excel->addInfoRow('Generation Info');
$excel->addEmptyRow();
$excel->addFilterSection($filters);
$excel->addEmptyRow();
$excel->addHeader($headers);
$excel->addRows($data);
$excel->addEmptyRow(2);
$excel->addSummaryRow('TOTAL:', $total);
```

---

## 📝 Tips & Tricks

1. **Row Height**: Gunakan height 35-40 untuk title, 25-28 untuk header
2. **Font Size**: Title 18-20, Header 11-12, Data 10-11
3. **Merge Cells**: Gunakan untuk title dan info row (sudah otomatis di method)
4. **Freeze Panes**: Freeze setelah header untuk kemudahan scroll
5. **Column Width**: Set setelah add header untuk hasil optimal

---

## 🎯 Result Preview

Dengan implementation di atas, Excel yang dihasilkan akan memiliki:

✅ Header perusahaan yang profesional
✅ Judul besar dengan background berwarna
✅ Informasi filter yang jelas dengan icons
✅ Table header dengan styling menarik
✅ Data dengan zebra striping untuk readability
✅ Summary section dengan highlight
✅ Footer dengan informasi sistem
✅ Freeze panes untuk navigasi mudah

---

Dokumentasi dibuat: 2025-12-10
