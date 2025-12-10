# Quick Installation Guide - Custom Excel Library

## 🚀 Instalasi Lokal (Development)

### 1. Install Package
```bash
composer require phpoffice/phpspreadsheet:^2.0
```

### 2. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload -o
```

### 3. Test
```bash
php test-custom-excel.php
```

## 📦 Files Yang Sudah Dibuat

✅ Library sudah siap digunakan! File-file berikut sudah dibuat:

### Core Library
- `app/Services/ExcelService.php` - Service utama
- `app/Facades/Excel.php` - Facade untuk akses mudah
- `app/Providers/ExcelServiceProvider.php` - Service provider (sudah terdaftar)

### Export Classes
- `app/Exports/BookingReportExport.php` - Versi lama (maatwebsite)
- `app/Exports/BookingReportExportNew.php` - Versi baru (custom)

### Controller Updates
- `app/Http/Controllers/Reports/BookingReportController.php` - Sudah diupdate dengan fallback

### Configuration
- `config/app.php` - ExcelServiceProvider & Facade sudah terdaftar
- `composer.json` - phpoffice/phpspreadsheet sudah ditambahkan

### Documentation & Testing
- `EXCEL-LIBRARY-GUIDE.md` - Dokumentasi lengkap
- `test-custom-excel.php` - Script testing
- `verify-excel.php` - Script verifikasi (untuk production)
- `DEPLOY-CPANEL.md` - Panduan deploy ke cPanel

## 🔧 Deploy ke cPanel (Production)

### 1. Upload ke Server
Upload semua file via FTP/File Manager ke folder aplikasi

### 2. SSH ke Server
Login ke terminal SSH cPanel

### 3. Install Dependencies
```bash
cd /home/username/public_html
composer install --optimize-autoloader --no-dev
```

### 4. Verify Installation
```bash
php verify-excel.php
```

Pastikan semua checks menunjukkan ✓

### 5. Clear & Optimize
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload -o
php artisan config:cache
php artisan route:cache
```

### 6. Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## ✅ Verifikasi

### Test di Browser
Akses endpoint export di aplikasi Anda:
```
https://yourdomain.com/reports/booking/export
```

### Test via CLI
```bash
php artisan tinker
```

Lalu jalankan:
```php
$excel = new \App\Services\ExcelService();
$excel->setTitle('Test');
$excel->addHeader(['A', 'B', 'C']);
$excel->addRow([1, 2, 3]);
echo "Success!";
```

## 🎯 Cara Menggunakan

### Quick Export (Simple)
```php
use App\Facades\Excel;

return Excel::export(
    data: [
        [1, 'John', 'john@example.com'],
        [2, 'Jane', 'jane@example.com'],
    ],
    headers: ['ID', 'Name', 'Email'],
    filename: 'users.xlsx'
);
```

### Custom Export (Advanced)
Lihat contoh di `app/Exports/BookingReportExportNew.php`

## 🐛 Troubleshooting

### Error: Class not found
```bash
composer dump-autoload -o
php artisan config:clear
```

### Error: Permission denied
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Error: Memory limit
Edit `.env`:
```
MEMORY_LIMIT=512M
```

Atau di kode:
```php
ini_set('memory_limit', '512M');
```

## 📚 Dokumentasi Lengkap

Baca file `EXCEL-LIBRARY-GUIDE.md` untuk:
- Contoh penggunaan lengkap
- Fitur-fitur advanced
- Custom styling
- Best practices

## 💡 Tips

1. **Selalu gunakan try-catch** untuk error handling
2. **Test di local** sebelum deploy
3. **Backup database** sebelum deploy production
4. **Monitor memory usage** untuk export data besar
5. **Gunakan chunking** untuk data > 10,000 rows

## ⚡ Performance Tips

### Untuk Data Besar (>10,000 rows)
```php
User::chunk(1000, function ($users) use ($excel) {
    foreach ($users as $user) {
        $excel->addRow([$user->id, $user->name]);
    }
});
```

### Optimize Composer Autoload
```bash
composer dump-autoload -o --no-dev
```

## 🎉 Ready to Use!

Library sudah siap digunakan. Export pertama Anda:

```php
// Di Controller
public function export()
{
    $excel = new \App\Services\ExcelService();
    $excel->setTitle('My Report')
        ->addHeader(['ID', 'Name', 'Email'])
        ->addRows([
            [1, 'John Doe', 'john@example.com'],
            [2, 'Jane Doe', 'jane@example.com'],
        ])
        ->freezeFirstRow();

    return $excel->download('my-report.xlsx');
}
```

Selamat menggunakan! 🚀
