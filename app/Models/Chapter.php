<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    // Fillable

    protected $fillable = [
        'manhwa_id',
        'chapter_number',
        'title',
        'slug',
        'thumbnail',
    ];

    // Manhwa
    public function manhwa()
    {
        return $this->belongsTo(Manhwa::class, 'manhwa_id');
    }

    // Pages
    public function pages()
    {
        return $this->hasMany(Page::class, 'chapter_id');
    }
}