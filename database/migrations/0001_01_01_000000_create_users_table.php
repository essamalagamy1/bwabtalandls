<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_key')->nullable();
            $table->string('phone')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('language')->default('en');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('provider_token')->nullable();
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
