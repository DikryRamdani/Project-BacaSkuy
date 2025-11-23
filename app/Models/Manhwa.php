<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Manhwa extends Model
{
    use HasFactory;

    // Fillable
    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'status',
        'author',
        'artist',
        'format',
    ];

    // Relations
    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'manhwa_id');
    }

    // Genres
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_manhwas', 'manhwa_id', 'genre_id');
    }

    // Mutator: normalisasi cover_image
    protected function coverImage(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if (is_null($value)) {
                    return null;
                }
                // normalisasi URL
                $v = preg_replace('#^https?://[^/]+/#', '', $value);
                $v = str_replace('storage/', '', $v);
                return ltrim($v, '/');
            },
            get: fn ($value) => $value
        );
    }

    // Accessor: full cover URL
    protected function coverUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $cv = $attributes['cover_image'] ?? null;
                return $cv ? asset('storage/' . ltrim($cv, '/')) : null;
            }
        );
    }

    // Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Approved parent comments
    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->approved()->parentOnly()->latest()->with(['user', 'replies']);
    }

    // Ratings
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Average rating
    public function averageRating()
    {
        return (float) ($this->ratings()->avg('rating') ?? 0);
    }

    // Favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Favorited check
    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    // Featured
    public function featured()
    {
        return $this->hasOne(FeaturedManhwa::class);
    }

    // Reading history
    public function readingHistory()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    // Reading history by user
    public function getReadingHistoryFor($userId)
    {
        return $this->readingHistory()->where('user_id', $userId)->first();
    }
}