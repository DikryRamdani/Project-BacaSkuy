<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Up
    public function up(): void
    {
        Schema::create('genres', function(Blueprint $table) {
            $table -> id();
            $table -> string('name');
            $table -> string('slug')->unique();
            $table -> timestamps();
        });
    }

    // Down
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};
