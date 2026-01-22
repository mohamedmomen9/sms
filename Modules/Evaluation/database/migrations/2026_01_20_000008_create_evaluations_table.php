<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50);
            $table->foreignId('assessment_id')->constrained('assessments');
            $table->foreignId('term_id')->constrained('terms');
            $table->string('course_code', 20);
            $table->foreignId('instructor_id')->constrained('teachers');
            $table->json('responses');
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->unique(
                ['student_id', 'assessment_id', 'term_id', 'course_code', 'instructor_id'],
                'unique_evaluation'
            );
            $table->foreign('student_id')->references('student_id')->on('students');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
