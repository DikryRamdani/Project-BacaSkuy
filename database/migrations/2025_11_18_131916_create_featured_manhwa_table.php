<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Up
    public function up(): void
    {
        Schema::create('featured_manhwa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manhwa_id')->constrained('manhwas')->onDelete('cascade');
            $table->integer('order')->default(0)->comment('Display order in carousel');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('manhwa_id');
        });
    }

    // Down
    public function down(): void
    {
        Schema::dropIfExists('featured_manhwa');
    }
};
