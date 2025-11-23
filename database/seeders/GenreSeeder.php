<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Mystery',
            'Romance',
            'Sci-Fi',
            'Slice of Life',
            'Sports',
            'Supernatural',
            'Thriller',
            'Tragedy',
            'Isekai',
        ];

        foreach ($genres as $genre) {
            Genre::firstOrCreate(
                ['slug' => Str::slug($genre)],
                [
                    'name' => $genre,
                    'slug' => Str::slug($genre),
                ]
            );
        }

        $this->command->info('✓ Genres seeded successfully!');
    }
}
