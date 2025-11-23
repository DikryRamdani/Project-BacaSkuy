<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'manhwa_id',
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
