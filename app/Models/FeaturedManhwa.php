<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeaturedManhwa extends Model
{
    use HasFactory;

    protected $table = 'featured_manhwa';

    protected $fillable = [
        'manhwa_id',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Manhwa
    public function manhwa(): BelongsTo
    {
        return $this->belongsTo(Manhwa::class);
    }

    // Scope: active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: ordered
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
