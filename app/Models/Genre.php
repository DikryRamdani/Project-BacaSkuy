<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    // Fillable

    protected $fillable = [
        'name',
        'slug',
    ];

    // Manhwas
    public function manhwas()
    {
        return $this->belongsToMany(Manhwa::class, 'genre_manhwas', 'genre_id', 'manhwa_id');
    }
}