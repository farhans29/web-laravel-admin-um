# Panduan Deploy Laravel ke cPanel

## Masalah: Target class [excel] does not exist

Error ini terjadi karena package Laravel Excel belum terinstall dengan benar di server cPanel.

## Solusi Step-by-Step:

### 1. Upload File ke cPanel
- Upload semua file aplikasi ke folder `public_html` atau subdomain
- Pastikan file `.env` sudah di-upload dan dikonfigurasi dengan benar

### 2. Akses Terminal SSH di cPanel
Masuk ke cPanel → Terminal atau gunakan SSH client

### 3. Masuk ke Directory Aplikasi
```bash
cd public_html  # atau path aplikasi Anda
```

### 4. Install Dependencies dengan Composer
```bash
composer install --optimize-autoloader --no-dev
```

Jika tidak ada composer, install dulu:
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Lalu gunakan:
```bash
php composer.phar install --optimize-autoloader --no-dev
```

### 5. Publish Config Laravel Excel (Jika Belum)
```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

### 6. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 7. Regenerate Autoload
```bash
composer dump-autoload -o
```

### 8. Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 9. Optimize untuk Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Verifikasi Instalasi

Jalankan script verifikasi:
```bash
php verify-excel.php
```

## Troubleshooting

### Jika masih error setelah langkah di atas:

1. **Cek PHP Version**
   ```bash
   php -v
   ```
   Pastikan minimal PHP 8.2 (sesuai requirement composer.json)

2. **Cek PHP Extensions**
   ```bash
   php -m | grep -E '(zip|xml|gd|mbstring)'
   ```
   Extensions yang diperlukan:
   - zip
   - xml
   - gd
   - mbstring
   - fileinfo

3. **Force Reinstall Laravel Excel**
   ```bash
   composer remove maatwebsite/excel
   composer require maatwebsite/excel:^3.1
   php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
   composer dump-autoload -o
   ```

4. **Cek File Permissions**
   Pastikan semua file bisa dibaca oleh web server:
   ```bash
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   chmod -R 775 storage bootstrap/cache
   ```

5. **Cek .env Configuration**
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:...  # harus ada
   ```

6. **Clear All Cache (Force)**
   ```bash
   rm -rf bootstrap/cache/*.php
   rm -rf storage/framework/cache/data/*
   rm -rf storage/framework/sessions/*
   rm -rf storage/framework/views/*
   php artisan cache:clear
   php artisan config:clear
   ```

## Cara Setup cPanel .htaccess

Jika aplikasi Laravel ada di subfolder, tambahkan di `.htaccess` root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## Catatan Penting

1. **Jangan commit vendor/** - Selalu jalankan `composer install` di server
2. **Jangan commit .env** - Buat manual di server
3. **Set APP_ENV=production** di .env production
4. **Set APP_DEBUG=false** di production
5. **Generate APP_KEY** jika belum ada: `php artisan key:generate`

## Kontak Support

Jika masih ada masalah, hubungi hosting support atau tim development.
