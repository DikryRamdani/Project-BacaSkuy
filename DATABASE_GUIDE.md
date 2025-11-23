# 📦 Database Backup & Restore Guide

## 🎯 Strategi Backup

### **Data Static (Gunakan Seeder)**

✅ Genres
✅ Admin User
✅ Settings/Config

### **Data Dinamis (Gunakan SQL Backup)**

✅ Manhwa (user-uploaded)
✅ Chapters
✅ Pages
✅ User accounts
✅ Genre-Manhwa relations

---

## 🔄 Workflow: Migrasi dengan Data Aman

### **Skenario 1: Refresh dengan Seeder (Hanya Data Static)**

```bash
# 1. Migrate fresh
php artisan migrate:fresh

# 2. Seed data static (genres + admin)
php artisan db:seed

# 3. Upload manhwa manual lagi
# (atau restore dari backup SQL - lihat skenario 2)
```

**Hasil:**

-   ✅ Genres kembali (15 genres)
-   ✅ Admin user kembali (admin@bacaskuy.com)
-   ❌ Manhwa hilang (harus upload ulang)
-   ❌ Chapters hilang (harus upload ulang)

---

### **Skenario 2: Backup Full + Restore (Semua Data Aman)**

```bash
# 1. BACKUP DULU!
backup-database.bat

# File tersimpan di: database/backups/backup_YYYYMMDD_HHMMSS.sql

# 2. Migrate fresh (kalau perlu)
php artisan migrate:fresh

# 3. RESTORE backup
restore-database.bat
# Pilih file backup yang mau di-restore

# 4. (Opsional) Seed data tambahan
php artisan db:seed
```

**Hasil:**

-   ✅ Semua data kembali seperti sebelum fresh
-   ✅ Manhwa tetap ada
-   ✅ Chapters tetap ada
-   ✅ Users tetap ada

---

## 📝 Commands

### Backup Database

```bash
# Windows
backup-database.bat

# Manual (MySQL)
mysqldump -u root BacaSkuy > database/backups/manual_backup.sql
```

### Restore Database

```bash
# Windows (Interactive)
restore-database.bat

# Manual (MySQL)
mysql -u root BacaSkuy < database/backups/backup_20251113_120000.sql
```

### Seed Data

```bash
# Seed semua (Genres + Admin)
php artisan db:seed

# Seed specific seeder
php artisan db:seed --class=GenreSeeder
php artisan db:seed --class=AdminSeeder
```

---

## 🚨 PENTING!

### ⚠️ JANGAN DI PRODUCTION:

```bash
php artisan migrate:fresh      # ← BAHAYA! Hapus semua data
php artisan migrate:refresh    # ← BAHAYA! Hapus semua data
php artisan migrate:reset      # ← BAHAYA! Hapus semua data
```

### ✅ AMAN DI PRODUCTION:

```bash
php artisan migrate            # ← AMAN! Hanya jalankan migration baru
php artisan db:seed            # ← AMAN! Tambah data static
```

---

## 📊 Default Seeded Data

### Genres (15 items):

-   Action
-   Adventure
-   Comedy
-   Drama
-   Fantasy
-   Horror
-   Mystery
-   Romance
-   Sci-Fi
-   Slice of Life
-   Sports
-   Supernatural
-   Thriller
-   Tragedy
-   Isekai

### Admin User:

-   **Email:** admin@bacaskuy.com
-   **Password:** admin123
-   **is_admin:** true

---

## 💡 Tips

1. **Backup sebelum migrate:fresh** - Selalu!
2. **Test di local dulu** - Jangan langsung di production
3. **Git ignore backups** - Jangan commit file SQL besar
4. **Compress backup** - Kalau file besar, zip dulu
5. **Schedule backup** - Buat backup rutin (cron/task scheduler)

---

## 📁 Folder Structure

```
database/
├── backups/              ← SQL backup files (git ignored)
│   ├── backup_20251113_120000.sql
│   └── backup_20251113_150000.sql
├── migrations/
└── seeders/
    ├── AdminSeeder.php   ← Seed admin user
    ├── GenreSeeder.php   ← Seed genres
    └── DatabaseSeeder.php
```

---

## 🔧 Troubleshooting

### Error: "Access denied for user 'root'"

-   Cek password MySQL di .env
-   Pastikan MySQL service running

### Backup file terlalu besar

-   Compress dengan WinRAR/7zip
-   Atau backup per table:
    ```bash
    mysqldump -u root BacaSkuy manhwas chapters > content_backup.sql
    ```

### Restore gagal

-   Cek apakah database BacaSkuy sudah dibuat
-   Drop database dulu lalu buat ulang:
    ```sql
    DROP DATABASE IF EXISTS BacaSkuy;
    CREATE DATABASE BacaSkuy;
    ```
