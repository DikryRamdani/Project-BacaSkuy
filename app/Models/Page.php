<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    // Fillable

    protected $fillable = [
        'chapter_id',
        'page_number',
        'image_url',
    ];

    // Chapter
    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }
}