<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Up
    public function up(): void
    {
        Schema::create('chapters', function(Blueprint $table) {
            $table -> id();
            $table -> foreignId('manhwa_id')->constrained('manhwas')->onDelete('cascade');
            $table -> string('chapter_number');
            $table -> string('title')->nullable();
            $table -> string('slug')->unique();
            $table -> timestamps();
        });
    }

    // Down
    public function down(): void
    {
        Schema::dropIfExists('chapter');
    }
};
