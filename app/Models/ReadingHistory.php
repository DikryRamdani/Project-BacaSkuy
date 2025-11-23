<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    use HasFactory;

    protected $table = 'reading_history';

    protected $fillable = [
        'user_id',
        'manhwa_id',
        'chapter_id',
        'last_read_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
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

    // Chapter
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    // Scope: recent
    public function scopeRecent($query)
    {
        return $query->orderBy('last_read_at', 'desc');
    }
}
