<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->unsignedSmallInteger('academic_year_from')->nullable()->after('end_date');
            $table->unsignedSmallInteger('academic_year_to')->nullable()->after('academic_year_from');
        });
    }

    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn(['academic_year_from', 'academic_year_to']);
        });
    }
};
