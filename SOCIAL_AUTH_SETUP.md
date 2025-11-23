# 🔐 Social Authentication Setup Guide

## Fitur Baru yang Ditambahkan

### ✅ Sistem Komentar
- **Komentar di halaman detail manhwa**
- **AJAX form submission** untuk UX yang smooth
- **Real-time comment posting** tanpa page reload
- **Edit/Delete komentar** untuk pemilik dan admin
- **Avatar integration** dengan Gravatar fallback
- **Comment moderation** dengan approval system

### ✅ Social Login
- **Google OAuth**
- **Facebook Login**  
- **GitHub Authentication**
- **Account linking** - Link social ke email yang sudah ada
- **Auto profile picture** dari social media

---

## 🚀 Setup Social Authentication

### 1. Google OAuth Setup

**Buat Google OAuth App:**
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create new project atau pilih existing project
3. Enable Google+ API
4. Go to **Credentials** → **Create Credentials** → **OAuth 2.0 Client ID**
5. Set **Application type** = Web application
6. Add **Authorized redirect URIs**:
   - `http://localhost:8000/auth/google/callback`
   - `http://your-domain.com/auth/google/callback`

**Copy credentials dan tambahkan ke .env:**
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

### 2. Facebook Login Setup

**Buat Facebook App:**
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create App → Consumer
3. Add **Facebook Login** product
4. Go to **Facebook Login** → **Settings**
5. Add **Valid OAuth Redirect URIs**:
   - `http://localhost:8000/auth/facebook/callback`
   - `http://your-domain.com/auth/facebook/callback`

**Copy App ID & Secret ke .env:**
```env
FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URL=http://localhost:8000/auth/facebook/callback
```

### 3. GitHub OAuth Setup

**Buat GitHub OAuth App:**
1. Go to GitHub → **Settings** → **Developer settings** → **OAuth Apps**
2. Click **New OAuth App**
3. Fill:
   - **Application name**: BacaSkuy
   - **Homepage URL**: `http://localhost:8000`
   - **Authorization callback URL**: `http://localhost:8000/auth/github/callback`

**Copy Client ID & Secret ke .env:**
```env
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URL=http://localhost:8000/auth/github/callback
```

---

## 📝 Complete .env Configuration

Tambahkan semua konfigurasi social auth ke file .env:

```env
# Database - gunakan SQLite untuk development
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

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

---

## 🎯 Testing Social Login

### Quick Test Steps:

1. **Start development server:**
   ```bash
   php artisan serve
   ```

2. **Go to login page:**
   - Visit: `http://localhost:8000/login`
   - Klik tombol "Continue with Google/Facebook/GitHub"

3. **Test flow:**
   - Login dengan social account
   - Check apakah user profile ter-create
   - Test comment di manhwa page
   - Test logout dan login ulang

### Debugging Tips:

**Jika social login error:**
- Check `.env` file sudah benar
- Pastikan callback URL sama persis
- Clear config cache: `php artisan config:clear`
- Check Laravel log: `storage/logs/laravel.log`

---

## 🔧 Database Schema Changes

### New Tables:
- **`comments`** - Menyimpan komentar user di manhwa
- **New columns in `users`:**
  - `provider` - Social platform (google/facebook/github)
  - `provider_id` - Social media user ID
  - `avatar` - Profile picture URL

### Migration Commands:
```bash
# Sudah di-run, tapi untuk referensi:
php artisan migrate

# Jika ingin rollback:
php artisan migrate:rollback --step=2
```

---

## 🎨 UI/UX Features

### Comment System:
- **Real-time posting** tanpa page refresh
- **AJAX edit/delete** dengan confirmation
- **Toast notifications** untuk feedback
- **Character count** untuk comment limit
- **Responsive design** untuk mobile

### Social Login UI:
- **Branded buttons** dengan icon yang sesuai
- **Clean separation** antara social dan email login
- **Error handling** dengan user-friendly messages
- **Loading states** saat redirect ke provider

---

## 🚀 Production Deployment

### Update Callback URLs:
Ganti semua callback URL dari localhost ke production domain:

```env
GOOGLE_REDIRECT_URL=https://yourdomain.com/auth/google/callback
FACEBOOK_REDIRECT_URL=https://yourdomain.com/auth/facebook/callback
GITHUB_REDIRECT_URL=https://yourdomain.com/auth/github/callback
```

### Security Considerations:
- ✅ **CSRF protection** untuk all forms
- ✅ **XSS protection** dengan Laravel's `e()` helper
- ✅ **SQL injection protection** dengan Eloquent ORM
- ✅ **Rate limiting** bisa ditambah di routes
- ✅ **Input validation** untuk comments

---

## 📊 New Routes Added

### Social Authentication:
```php
GET  /auth/{provider}           - Redirect to social provider
GET  /auth/{provider}/callback  - Handle callback from provider
```

### Comment System:
```php
POST   /manhwa/{manhwa}/comments - Create comment
PUT    /comments/{comment}       - Update comment
DELETE /comments/{comment}       - Delete comment
```

---

## 🎯 Next Steps / Possible Enhancements

### 1. **Comment Moderation Panel (Admin)**
- Admin dashboard untuk approve/reject comments
- Bulk actions untuk comment management
- User blocking/banning system

### 2. **Comment Features**
- Reply to comments (nested comments)
- Like/dislike system
- Mention other users with @username
- Comment reporting system

### 3. **Social Features**
- User profiles dengan activity history
- Follow/unfollow other users
- Comment notifications
- Email digest untuk aktivitas

### 4. **Security Enhancements**
- Two-factor authentication
- Account recovery via social
- Session management
- IP-based restrictions

---

## 🐛 Troubleshooting

### Common Issues:

**1. "Invalid redirect URI"**
- Check callback URL di social platform settings
- Pastikan exact match dengan .env

**2. "Comments not loading"**
- Check CSRF token di page source
- Inspect browser console untuk JS errors
- Verify database has comments table

**3. "Social login not working"**
- Clear config cache: `php artisan config:clear`
- Check .env variables loaded: `php artisan tinker` → `config('services.google')`
- Verify SSL certificate di production

**4. "AJAX forms not working"**
- Check CSRF meta tag di layout
- Verify JavaScript console for errors
- Ensure user is authenticated

---

## 📁 Files Modified/Added

### New Files:
- `app/Models/Comment.php`
- `app/Http/Controllers/CommentController.php`
- `app/Http/Controllers/SocialAuthController.php`
- `database/migrations/2025_11_18_032843_create_comments_table.php`
- `database/migrations/2025_11_18_032849_add_social_auth_to_users_table.php`

### Modified Files:
- `app/Models/User.php` - Added social auth fields & avatar
- `app/Models/Manhwa.php` - Added comments relationship  
- `routes/web.php` - Added social & comment routes
- `config/services.php` - Added social provider configs
- `resources/views/auth/login.blade.php` - Added social buttons
- `resources/views/auth/register.blade.php` - Added social buttons
- `resources/views/public/detail.blade.php` - Added comment section
- `resources/views/layouts/app.blade.php` - Added CSRF token

---

Selamat! 🎉 Fitur komentar dan social authentication sudah siap digunakan.