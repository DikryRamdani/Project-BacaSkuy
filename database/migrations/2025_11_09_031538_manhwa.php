<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Up
    public function up(): void
    {
        Schema::create('manhwas', function(Blueprint $table) {
            $table -> id();
            $table -> string('title');
            $table -> string('slug')->unique();
            $table -> text('description')->nullable();
            $table -> string('cover_image')->nullable();
            $table -> enum('status', ['ongoing', 'completed']);
            $table -> string('author')->nullable();
            $table -> string('artist')->nullable();
            $table -> enum('format', ['manga', 'manhwa', 'manhua'])->nullable();
            $table -> timestamps();
        });
    }

    // Down
    public function down(): void
    {
        Schema::dropIfExists('manhwas');
    }
};
