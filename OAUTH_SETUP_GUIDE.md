# OAuth Social Authentication Setup Guide

Panduan lengkap untuk mengatur autentikasi social login dengan Google, Facebook, dan GitHub di BacaSkuy.

## Daftar Isi

1. [Google OAuth](#google-oauth)
2. [Facebook OAuth](#facebook-oauth)
3. [GitHub OAuth](#github-oauth)
4. [Konfigurasi .env](#konfigurasi-env)

---

## Google OAuth

### Langkah 1: Buat Project di Google Cloud Console

1. Kunjungi [Google Cloud Console](https://console.cloud.google.com/)
2. Login dengan akun Google Anda
3. Klik **Select a project** di bagian atas, lalu klik **New Project**
4. Beri nama project (contoh: "BacaSkuy")
5. Klik **Create**

### Langkah 2: Aktifkan Google+ API

1. Di menu sebelah kiri, pilih **APIs & Services** > **Library**
2. Cari "Google+ API" atau "Google Identity"
3. Klik pada API tersebut
4. Klik tombol **Enable**

### Langkah 3: Buat OAuth Credentials

1. Di menu sebelah kiri, pilih **APIs & Services** > **Credentials**
2. Klik **Create Credentials** > **OAuth client ID**
3. Jika diminta, konfigurasikan OAuth consent screen:
    - User Type: **External**
    - App name: BacaSkuy
    - User support email: email Anda
    - Developer contact information: email Anda
    - Klik **Save and Continue**
    - Pada Scopes, klik **Save and Continue**
    - Pada Test users (optional), klik **Save and Continue**
4. Kembali ke Credentials, pilih **Application type**: **Web application**
5. Beri nama (contoh: "BacaSkuy Web Client")
6. Authorized JavaScript origins:
    ```
    http://localhost
    http://localhost:8000
    ```
7. Authorized redirect URIs:
    ```
    http://localhost:8000/auth/google/callback
    http://localhost/auth/google/callback
    ```
8. Klik **Create**
9. Salin **Client ID** dan **Client Secret**

### Langkah 4: Tambahkan ke .env

```env
GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

---

## Facebook OAuth

### Langkah 1: Buat App di Facebook Developers

1. Kunjungi [Facebook Developers](https://developers.facebook.com/)
2. Login dengan akun Facebook Anda
3. Klik **My Apps** di kanan atas
4. Klik **Create App**
5. Pilih use case: **Consumer** atau **None**
6. Klik **Next**
7. Beri nama app (contoh: "BacaSkuy")
8. Contact email: email Anda
9. Klik **Create App**

### Langkah 2: Setup Facebook Login

1. Di dashboard app, cari **Facebook Login** di Add Products
2. Klik **Set Up**
3. Pilih platform **Web**
4. Masukkan Site URL:
    ```
    http://localhost:8000
    ```
5. Klik **Save**
6. Di sidebar kiri, pilih **Facebook Login** > **Settings**
7. Di **Valid OAuth Redirect URIs**, tambahkan:
    ```
    http://localhost:8000/auth/facebook/callback
    http://localhost/auth/facebook/callback
    ```
8. Klik **Save Changes**

### Langkah 3: Dapatkan App Credentials

1. Di sidebar kiri, pilih **Settings** > **Basic**
2. Salin **App ID** dan **App Secret**
    - Untuk melihat App Secret, klik **Show**

### Langkah 4: Mode Development

-   App secara default dalam mode Development
-   Hanya akun yang ditambahkan sebagai tester yang bisa login
-   Untuk menambah tester: **Roles** > **Test Users** atau **Add Facebook Testers**
-   Untuk production, submit app untuk review di **App Review**

### Langkah 5: Tambahkan ke .env

```env
FACEBOOK_CLIENT_ID=your_app_id_here
FACEBOOK_CLIENT_SECRET=your_app_secret_here
FACEBOOK_REDIRECT_URL=http://localhost:8000/auth/facebook/callback
```

---

## GitHub OAuth

### Langkah 1: Buat OAuth App di GitHub

1. Kunjungi [GitHub Settings](https://github.com/settings/developers)
2. Login dengan akun GitHub Anda
3. Di sidebar kiri, pilih **OAuth Apps**
4. Klik **New OAuth App** atau **Register a new application**

### Langkah 2: Isi Form Registrasi

1. **Application name**: BacaSkuy
2. **Homepage URL**:
    ```
    http://localhost:8000
    ```
3. **Application description** (optional): Manhwa reading platform
4. **Authorization callback URL**:
    ```
    http://localhost:8000/auth/github/callback
    ```
5. Klik **Register application**

### Langkah 3: Generate Client Secret

1. Setelah app terbuat, Anda akan melihat **Client ID**
2. Klik **Generate a new client secret**
3. Salin **Client ID** dan **Client Secret** yang baru dibuat
    - **PENTING**: Client Secret hanya ditampilkan sekali, simpan dengan aman!

### Langkah 4: Tambahkan ke .env

```env
GITHUB_CLIENT_ID=your_client_id_here
GITHUB_CLIENT_SECRET=your_client_secret_here
GITHUB_REDIRECT_URL=http://localhost:8000/auth/github/callback
```

---

## Konfigurasi .env

Setelah mendapatkan semua credentials, pastikan file `.env` Anda terlihat seperti ini:

```env
APP_URL=http://localhost:8000

# Social Authentication
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"

FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URL="${APP_URL}/auth/facebook/callback"

GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URL="${APP_URL}/auth/github/callback"
```

## Testing

Setelah konfigurasi selesai:

1. Restart Laravel development server:

    ```bash
    php artisan serve
    ```

2. Clear cache:

    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```

3. Kunjungi halaman login: http://localhost:8000/login

4. Klik tombol social login (Google/Facebook/GitHub) untuk test

## Troubleshooting

### Error: "redirect_uri_mismatch"

-   Pastikan URL callback di provider OAuth sama persis dengan yang di `.env`
-   Periksa protokol (http vs https)
-   Periksa port (misalnya :8000)

### Error: "Invalid client"

-   Periksa kembali Client ID dan Client Secret
-   Pastikan tidak ada spasi ekstra di `.env`

### Facebook: "App Not Setup"

-   Pastikan Facebook Login sudah diaktifkan di dashboard
-   Tambahkan akun Anda sebagai tester jika app masih dalam Development mode

### Google: "Access blocked: This app's request is invalid"

-   Pastikan OAuth consent screen sudah dikonfigurasi
-   Tambahkan test users jika app dalam testing mode
-   Periksa authorized redirect URIs

### GitHub: "The redirect_uri MUST match the registered callback URL"

-   Periksa Authorization callback URL di GitHub OAuth App settings
-   Pastikan sesuai dengan URL di `.env`

## Catatan Penting

1. **Keamanan**:

    - Jangan commit file `.env` ke Git
    - Simpan Client Secret dengan aman
    - Gunakan HTTPS untuk production

2. **Production**:

    - Ganti semua `http://localhost:8000` dengan domain production Anda
    - Update redirect URLs di semua provider
    - Submit Facebook app untuk review jika diperlukan
    - Hapus mode testing di Google OAuth

3. **Email Verification**:
    - User dari social login otomatis ter-verifikasi
    - Beberapa provider mungkin tidak memberikan email (akan di-generate otomatis)

## Support

Jika mengalami masalah, periksa:

-   Laravel logs: `storage/logs/laravel.log`
-   Browser console untuk error JavaScript
-   Network tab di browser DevTools untuk melihat request/response

---

**Dibuat untuk BacaSkuy - Manhwa Reading Platform**
