<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Up
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider')->nullable(); // google, facebook, github, etc.
            $table->string('provider_id')->nullable(); // Social media user ID
            $table->string('avatar')->nullable(); // Profile picture URL
            $table->timestamp('email_verified_at')->nullable()->change(); // Make email verification optional for social login
            
            // Add index for faster social auth lookup
            $table->index(['provider', 'provider_id']);
        });
    }

    // Down
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['provider', 'provider_id']);
            $table->dropColumn(['provider', 'provider_id', 'avatar']);
        });
    }
};
