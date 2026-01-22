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
        // Pivot table for teachers and faculties (many-to-many)
        Schema::create('faculty_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('faculty_id')->constrained('faculties')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['teacher_id', 'faculty_id']);
        });

        // Pivot table for teachers and subjects (many-to-many)
        Schema::create('subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['teacher_id', 'subject_id']);
        });

        // Drop the old school_id column from teachers table since we're using faculties now
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id')->nullable();
        });
        
        Schema::dropIfExists('subject_teacher');
        Schema::dropIfExists('faculty_teacher');
    }
};
