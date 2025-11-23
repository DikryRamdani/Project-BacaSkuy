<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Up
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('manhwa_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_approved')->default(true); // For moderation if needed
            $table->timestamps();
            
            // Index for performance
            $table->index(['manhwa_id', 'created_at']);
        });
    }

    // Down
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
