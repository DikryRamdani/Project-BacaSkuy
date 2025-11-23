<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'manhwa_id',
        'parent_id',
        'content',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
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

    // Scope: approved
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope: latest
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Replies (approved latest)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user')->approved()->latest();
    }

    // Scope: parent only
    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }
}
