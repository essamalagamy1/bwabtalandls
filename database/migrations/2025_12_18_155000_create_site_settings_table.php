<?php

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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('theme')->default('template1');
            $table->json('description')->nullable();
            //            $table->string('logo_white')->nullable(); // صورة أبيض
            //            $table->string('logo_black')->nullable(); // صورة أسود
            //            $table->string('favicon')->nullable(); // أيقونة الموقع
            $table->string('color_primary')->nullable()->default('#f8a400');
            $table->string('color_secondary')->nullable()->default('#FFFEFC');
            $table->string('color_accent')->nullable()->default('#f8a400');
            $table->json('about_us')->nullable();
            $table->json('shipping_returns')->nullable();
            $table->json('privacy_policy')->nullable();
            $table->json('terms_and_conditions')->nullable();
            $table->json('address')->nullable();
            $table->json('refund_policy');
            $table->json('shipping_policy');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('google_client_id')->nullable();
            $table->string('google_client_secret')->nullable();
            $table->string('google_redirect_uri')->nullable()->default(config('app.url').'/auth/google/callback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
