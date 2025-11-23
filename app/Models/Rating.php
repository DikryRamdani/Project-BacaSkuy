<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'manhwa_id',
        'rating',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    // User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Manhwa
    public function manhwa(): BelongsTo
    {
        return $this->belongsTo(Manhwa::class);
    }
}
