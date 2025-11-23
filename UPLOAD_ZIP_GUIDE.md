# Upload Chapter - Format ZIP

## Perubahan Fitur

Fitur upload chapter telah diubah dari **PDF** menjadi **ZIP berisi gambar-gambar**.

### Keuntungan:

-   ✅ Tidak perlu Poppler (pdftoppm) atau Imagick
-   ✅ Proses lebih cepat (tidak perlu konversi PDF)
-   ✅ Lebih fleksibel (bisa langsung edit gambar sebelum upload)
-   ✅ Support berbagai format gambar (JPG, PNG, GIF, WebP)

---

## Cara Upload Chapter

### 1. Siapkan Gambar Chapter

-   Kumpulkan semua gambar halaman chapter dalam satu folder
-   Beri nama file secara berurutan, contoh:
    ```
    page_001.jpg
    page_002.jpg
    page_003.jpg
    ...
    ```
    atau
    ```
    001.png
    002.png
    003.png
    ...
    ```

**Tips:**

-   Gunakan angka dengan leading zero (001, 002, dst) agar urutan benar
-   Format gambar yang didukung: JPG, JPEG, PNG, GIF, WebP
-   Resolusi rekomendasi: 800-1200px lebar untuk manhwa/webtoon

### 2. Buat File ZIP

-   **Windows**:
    -   Pilih semua gambar → Klik kanan → **Send to** → **Compressed (zipped) folder**
-   **macOS**:
    -   Pilih semua gambar → Klik kanan → **Compress X items**
-   **Linux**:
    ```bash
    zip chapter.zip *.jpg
    ```

**PENTING:** Jangan masukkan gambar dalam subfolder — semua gambar harus di **root ZIP**.

```
✅ BENAR:
chapter.zip
├── page_001.jpg
├── page_002.jpg
└── page_003.jpg

❌ SALAH:
chapter.zip
└── chapter_folder/
    ├── page_001.jpg
    ├── page_002.jpg
    └── page_003.jpg
```

### 3. Upload via Admin Panel

1. Login sebagai admin
2. Navigasi ke **Admin → Chapter → Upload Chapter**
3. Isi form:
    - **Seri (Manhwa)**: Pilih seri manhwa
    - **Nomor Chapter**: Contoh: `1`, `2.5`, `100`
    - **Judul Chapter** (opsional): Contoh: "The Beginning"
    - **File ZIP**: Upload file ZIP yang sudah dibuat
    - **Thumbnail** (opsional): Gambar cover chapter (rekomendasi 96x128px)
4. Klik **Upload Chapter**

### 4. Verifikasi

Setelah upload berhasil, sistem akan:

-   ✅ Extract semua gambar dari ZIP
-   ✅ Rename gambar menjadi `page_1.jpg`, `page_2.jpg`, dst
-   ✅ Simpan ke `storage/app/public/chapters/{slug}/`
-   ✅ Buat record di tabel `pages` untuk setiap halaman
-   ✅ Hapus file ZIP temporary

Cek:

-   **Admin Chapter List**: Lihat jumlah halaman di kolom "Pages"
-   **Public Reader**: Buka chapter di front-end dan pastikan semua gambar tampil berurutan

---

## Troubleshooting

### Upload gagal: "The file exceeds your upload_max_filesize"

**Solusi**: Edit `php.ini` dan tingkatkan limit:

```ini
upload_max_filesize = 512M
post_max_size = 520M
memory_limit = 1024M
```

Restart server dev (`php artisan serve`).

---

### Gambar tidak berurutan / acak

**Penyebab**: Nama file tidak alphabetically sorted.

**Solusi**: Rename file dengan leading zero:

-   ❌ `page_1.jpg`, `page_10.jpg`, `page_2.jpg` (salah urutan)
-   ✅ `page_001.jpg`, `page_002.jpg`, `page_010.jpg` (benar)

Sistem menggunakan **natural sort** (strnatcasecmp) sehingga `page_1.jpg` akan diurutkan sebelum `page_10.jpg`.

---

### Error: "Gagal membuka file ZIP"

**Penyebab**:

-   File corrupt
-   Bukan file ZIP valid
-   Extension PHP `zip` tidak aktif

**Solusi**:

1. Cek extension di `php.ini`:
    ```ini
    extension=zip
    ```
2. Restart server
3. Verifikasi:
    ```powershell
    php -m | Select-String 'zip'
    ```

---

### Gambar tidak muncul di reader

**Penyebab**: Storage link belum dibuat.

**Solusi**:

```bash
php artisan storage:link
```

Ini membuat symbolic link dari `storage/app/public` ke `public/storage`.

---

## Migrasi dari Upload PDF (Lama)

Jika Anda punya chapter lama yang diupload via PDF:

1. **Ekstrak gambar dari PDF** (gunakan tool seperti [pdf2image](https://pypi.org/project/pdf2image/) atau Adobe Acrobat)
2. **Rename gambar** secara berurutan
3. **Buat ZIP** dan **upload ulang** via admin panel baru
4. **Hapus chapter lama** jika perlu

---

## Spesifikasi Teknis

-   **Max upload size**: 500MB (bisa diubah di controller)
-   **Supported formats**: JPG, JPEG, PNG, GIF, WebP
-   **Storage path**: `storage/app/public/chapters/{slug}/page_N.{ext}`
-   **Database**: Tabel `pages` dengan kolom:
    -   `chapter_id`
    -   `page_number`
    -   `image_url` (path relatif: `storage/chapters/{slug}/page_N.jpg`)

---

## FAQ

**Q: Bisa upload langsung folder tanpa ZIP?**  
A: Tidak, browser hanya support upload file. Gunakan ZIP untuk bundle gambar.

**Q: Apakah PDF masih didukung?**  
A: Tidak lagi — fitur PDF upload sudah dihapus untuk menyederhanakan kode dan menghilangkan dependency Poppler/Imagick.

**Q: Berapa maksimal halaman per chapter?**  
A: Tidak ada batasan hard-coded, tapi tergantung limit ZIP size (500MB default) dan memori PHP.

**Q: Format nama file harus tepat `page_001.jpg`?**  
A: Tidak harus, sistem akan mengurutkan alfabetis dan rename otomatis. Yang penting: nama berurutan (001, 002, dst).

---

**Happy uploading! 🚀**
