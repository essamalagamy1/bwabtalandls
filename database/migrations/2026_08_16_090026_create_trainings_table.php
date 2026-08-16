<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('week_id')->constrained('weeks')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['video', 'pdf', 'file', 'link']);
            $table->string('url')->nullable();
            $table->dateTime('publish_date');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
