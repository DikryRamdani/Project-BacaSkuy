<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManhwaController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminManhwaController;
use App\Http\Controllers\Admin\AdminChapterController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\Admin\FeaturedManhwaController;
use App\Http\Middleware\CheckIsAdmin;

/*
 * Route publik
 * - Halaman depan (home)
 * - Menampilkan detail manhwa
 * - Membaca chapter
 * - Download chapter sebagai PDF (jika ada)
 * - Menampilkan halaman genre
 * - Pencarian
 */
Route::get('/', [ManhwaController::class, 'home'])->name('home');

// Auth routes (register / login / logout)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Menampilkan detail manhwa berdasarkan slug
Route::get('/manhwa/{slug}', [ManhwaController::class, 'detail'])->name('manhwa.show');

// Pembaca chapter berdasarkan slug manhwa dan slug chapter
Route::get('/manhwa/{slug}/chapter/{chapter_slug}', [ManhwaController::class, 'reader'])->name('chapter.reader');

// AJAX suggestions untuk search (live suggestions)
Route::get('/search/suggest', [ManhwaController::class, 'suggest'])->name('search.suggest');

// Explore page - all manhwa with sorting and search
Route::get('/explore', [ExploreController::class, 'index'])->name('explore.index');

// Library page - user's favorite manhwa
    Route::get('/library', [ManhwaController::class, 'library'])->name('library.index');

// Grouped auth-protected routes
Route::middleware('auth')->group(function () {
    
    // Settings routes
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile-picture', [\App\Http\Controllers\SettingsController::class, 'updateProfilePicture'])->name('settings.profile-picture');
    Route::delete('/settings/profile-picture', [\App\Http\Controllers\SettingsController::class, 'removeProfilePicture'])->name('settings.profile-picture.remove');
    Route::put('/settings/username', [\App\Http\Controllers\SettingsController::class, 'updateUsername'])->name('settings.username');
    Route::put('/settings/email', [\App\Http\Controllers\SettingsController::class, 'updateEmail'])->name('settings.email');
    Route::put('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');

    // Comments
    Route::post('/manhwa/{manhwa}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Ratings (store action requires auth)
    Route::post('/manhwa/{manhwa}/rating', [RatingController::class, 'store'])->name('rating.store');

    // Favorites toggle
    Route::post('/manhwa/{manhwa}/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
});

// Public (non-auth) endpoints for status checks
Route::get('/manhwa/{manhwa}/rating/user', [RatingController::class, 'getUserRating'])->name('rating.user');
Route::get('/manhwa/{manhwa}/favorite/check', [FavoriteController::class, 'check'])->name('favorite.check');

/*
 * Route admin (dilindungi middleware CheckIsAdmin)
 *
 * Catatan / asumsi:
 * - Middleware ada di kelas `App\\Http\\Middleware\\CheckIsAdmin` dan dapat dipanggil langsung di sini.
 * - Controller admin menggunakan metode resource standar Laravel: index, create, store, show, edit, update, destroy.
 * - Semua route admin diprefiks dengan `/admin` dan diberi nama awalan `admin.` melalui opsi resource.
 */
Route::prefix('admin')->middleware([CheckIsAdmin::class])->group(function () {
    // Dashboard admin
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Resource routes untuk manhwa, chapter, dan genre di panel admin
    Route::resource('manhwa', AdminManhwaController::class, ['as' => 'admin']);
    Route::resource('chapter', AdminChapterController::class, ['as' => 'admin']);
    // Bulk delete untuk chapter
    Route::post('chapter/bulk-delete', [AdminChapterController::class, 'bulkDestroy'])->name('admin.chapter.bulkDestroy');
    Route::resource('genre', AdminGenreController::class, ['as' => 'admin']);
    
    // Featured Manhwa management
    Route::get('featured-manhwa', [FeaturedManhwaController::class, 'index'])->name('admin.featured-manhwa.index');
    Route::get('featured-manhwa/create', [FeaturedManhwaController::class, 'create'])->name('admin.featured-manhwa.create');
    Route::post('featured-manhwa', [FeaturedManhwaController::class, 'store'])->name('admin.featured-manhwa.store');
    Route::put('featured-manhwa/{featuredManhwa}', [FeaturedManhwaController::class, 'update'])->name('admin.featured-manhwa.update');
    Route::delete('featured-manhwa/{featuredManhwa}', [FeaturedManhwaController::class, 'destroy'])->name('admin.featured-manhwa.destroy');
    Route::post('featured-manhwa/{featuredManhwa}/toggle', [FeaturedManhwaController::class, 'toggleActive'])->name('admin.featured-manhwa.toggle');
});
