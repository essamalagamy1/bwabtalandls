<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->enum('selected_option', ['a', 'b', 'c', 'd']);
            $table->boolean('is_correct');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
