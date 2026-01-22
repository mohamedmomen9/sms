<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Multi-instructor support and session type flexibility:
     * - Creates session_types lookup table for schedule type categorization
     * - Creates course_offering_teacher pivot for many-to-many instructor assignment
     * - Adds session_type_id to course_schedules for type classification
     * - Migrates existing teacher_id data to the new pivot table
     */
    public function up(): void
    {
        // Session types lookup table (C, LAB, LECT, PR, TUT)
        Schema::create('session_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default session types
        DB::table('session_types')->insert([
            ['code' => 'C', 'name' => 'Class', 'description' => 'Regular class session', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LAB', 'name' => 'Laboratory', 'description' => 'Laboratory/practical session', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LECT', 'name' => 'Lecture', 'description' => 'Lecture session', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PR', 'name' => 'Practical', 'description' => 'Practical application session', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'TUT', 'name' => 'Tutorial', 'description' => 'Tutorial session', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Many-to-many pivot: course offerings <-> teachers (instructors)
        Schema::create('course_offering_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_offering_id')->constrained('course_offerings')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false); // Primary instructor flag
            $table->timestamps();

            $table->unique(['course_offering_id', 'teacher_id']);
            $table->index('teacher_id');
        });

        // Add session type to course schedules
        Schema::table('course_schedules', function (Blueprint $table) {
            $table->foreignId('session_type_id')
                ->nullable()
                ->after('course_offering_id')
                ->constrained('session_types')
                ->nullOnDelete();
        });

        // Migrate existing teacher_id data to the pivot table
        $offerings = DB::table('course_offerings')->whereNotNull('teacher_id')->get();
        foreach ($offerings as $offering) {
            DB::table('course_offering_teacher')->insert([
                'course_offering_id' => $offering->id,
                'teacher_id' => $offering->teacher_id,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Set default session type (LECT) for existing schedules
        $lectureType = DB::table('session_types')->where('code', 'LECT')->first();
        if ($lectureType) {
            DB::table('course_schedules')->update(['session_type_id' => $lectureType->id]);
        }

        // Remove the old teacher_id column (now using pivot table)
        Schema::table('course_offerings', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore teacher_id column
        Schema::table('course_offerings', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->after('term_id')->constrained('teachers')->nullOnDelete();
        });

        // Migrate primary instructors back to teacher_id
        $pivots = DB::table('course_offering_teacher')->where('is_primary', true)->get();
        foreach ($pivots as $pivot) {
            DB::table('course_offerings')
                ->where('id', $pivot->course_offering_id)
                ->update(['teacher_id' => $pivot->teacher_id]);
        }

        Schema::table('course_schedules', function (Blueprint $table) {
            $table->dropForeign(['session_type_id']);
            $table->dropColumn('session_type_id');
        });

        Schema::dropIfExists('course_offering_teacher');
        Schema::dropIfExists('session_types');
    }
};
