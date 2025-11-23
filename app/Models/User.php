<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'provider',
        'provider_id',
        'avatar',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    // Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Ratings
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Reading history
    public function readingHistory()
    {
        return $this->hasMany(ReadingHistory::class)->recent();
    }

    // Favorited manhwas
    public function favoriteManhwas()
    {
        return $this->belongsToMany(Manhwa::class, 'favorites');
    }

    // Avatar URL accessor
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return $this->avatar;
        }
        
        // Gravatar fallback
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=150";
    }

    // Social login check
    public function isSocialUser()
    {
        return !is_null($this->provider);
    }
}