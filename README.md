#KELOMPOK 8

Anggota : 
- Dikry Ramdani
- Mirza Ramadhan

# Project BacaSkuy

Project BacaSkuy adalah aplikasi web berbasis Laravel untuk mengelola dan menyajikan konten bacaan (mis. buku, artikel, paket konten). Aplikasi dirancang untuk memudahkan pengunggahan paket konten, pengelolaan aset, serta otentikasi pengguna melalui metode tradisional maupun social OAuth.

> Catatan: dokumentasi pendukung lain tersedia di repo: `DATABASE_GUIDE.md`, `OAUTH_SETUP_GUIDE.md`, `SOCIAL_AUTH_SETUP.md`, `UPLOAD_ZIP_GUIDE.md`.

## Tech stack

- Backend: Laravel (PHP) — struktur proyek menggunakan folder standar Laravel (app/, routes/, resources/).
- Templating: Blade (Laravel Blade templates).
- Frontend tooling: Node.js, npm, Vite (dikonfigurasi lewat `vite.config.js`).
- Testing: PHPUnit (`phpunit.xml` tersedia).
- Database: MySQL/PostgreSQL (lihat `DATABASE_GUIDE.md` untuk panduan setup dan migrasi).
- Authentication: OAuth / Social Auth (panduan pada `OAUTH_SETUP_GUIDE.md` dan `SOCIAL_AUTH_SETUP.md`).
- Penyimpanan & backup: ada skrip backup/restore dan folder backup (lihat file `.bat` dan `database/backups/`).

## Fitur utama

- Pengelolaan konten bacaan:
  - Impor paket konten melalui ZIP (format & langkah pada `UPLOAD_ZIP_GUIDE.md`).
  - Penyimpanan aset dan metadata terkait konten.
  - Penyajian konten lewat tampilan web (Blade views dan routes).
- Otentikasi:
  - Login tradisional.
  - Integrasi social OAuth untuk login dengan penyedia pihak ketiga (Google, Facebook, dll.) — konfigurasi ada di `OAUTH_SETUP_GUIDE.md` dan `SOCIAL_AUTH_SETUP.md`.
- Backup & restore:
  - Skrip otomatis untuk backup/restore database dan konten (`backup-database.bat`, `backup-content-only.bat`, `restore-database.bat`, `restore-content-only.bat`).
  - Panduan database di `DATABASE_GUIDE.md`.
- Frontend modern:
  - Build tooling menggunakan Vite, manajemen paket lewat `package.json`.
- Struktur yang mudah dikembangkan:
  - Pemisahan concerns antara models, controllers, routes, views, dan asset publik.

## Struktur penting repository

- app/                       : kode aplikasi (models, controllers, services)
- routes/                    : definisi route aplikasi
- resources/                 : views (Blade), aset front-end
- public/                    : file publik yang disajikan web server
- database/                  : migrasi, seeders, dan panduan `DATABASE_GUIDE.md`
- storage/                   : penyimpanan file yang diunggah
- composer.json              : dependensi PHP
- package.json               : dependensi JavaScript
- UPLOAD_ZIP_GUIDE.md        : panduan upload dan format ZIP untuk impor konten
