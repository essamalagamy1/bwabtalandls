<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('assignment_date');
            $table->boolean('is_active')->default(true)->after('passing_score');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn(['publish_date', 'is_published']);
            $table->boolean('is_active')->default(true)->after('url');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dateTime('assignment_date')->nullable();
            $table->dropColumn('is_active');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dateTime('publish_date')->nullable();
            $table->boolean('is_published')->default(false);
            $table->dropColumn('is_active');
        });
    }
};
