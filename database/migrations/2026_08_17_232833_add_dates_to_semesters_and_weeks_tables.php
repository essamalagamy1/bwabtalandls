<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('is_active');
            $table->date('end_date')->nullable()->after('start_date');
        });

        Schema::table('weeks', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('order');
            $table->date('start_date')->nullable()->after('is_active');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });

        Schema::table('weeks', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'start_date', 'end_date']);
        });
    }
};
