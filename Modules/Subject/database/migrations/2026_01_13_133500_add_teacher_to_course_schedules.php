<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add instructor assignment per schedule session.
     * Each session (lecture, lab, tutorial) can have a different instructor.
     */
    public function up(): void
    {
        Schema::table('course_schedules', function (Blueprint $table) {
            $table->foreignId('teacher_id')
                ->nullable()
                ->after('session_type_id')
                ->constrained('teachers')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_schedules', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};
