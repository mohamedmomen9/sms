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
        // 1. Add credit_hours to curriculum_subject pivot (Moving max_hours to curriculum level)
        if (Schema::hasTable('curriculum_subject')) {
            Schema::table('curriculum_subject', function (Blueprint $table) {
                if (!Schema::hasColumn('curriculum_subject', 'credit_hours')) {
                    $table->decimal('credit_hours', 4, 2)->default(3.0);
                }
            });
        }

        // 2. Create Subject Prerequisites Table
        if (!Schema::hasTable('subject_prerequisites')) {
            Schema::create('subject_prerequisites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
                $table->foreignId('prerequisite_id')->constrained('subjects')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['subject_id', 'prerequisite_id']);
            });
        }

        // 3. Create or Update Student/User Subjects Pivot (Enrollment)
        // User model implies belongsToMany, likely 'subject_user' table
        if (!Schema::hasTable('subject_user')) {
            Schema::create('subject_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
                $table->string('status')->default('enrolled'); // enrolled, completed, failed
                $table->string('grade')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('subject_user', function (Blueprint $table) {
                if (!Schema::hasColumn('subject_user', 'status')) {
                    $table->string('status')->default('enrolled');
                }
                if (!Schema::hasColumn('subject_user', 'grade')) {
                    $table->string('grade')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_prerequisites');

        if (Schema::hasTable('curriculum_subject')) {
            Schema::table('curriculum_subject', function (Blueprint $table) {
                $table->dropColumn('credit_hours');
            });
        }

        // We don't drop subject_user as it might be critical, but we can drop columns
        if (Schema::hasTable('subject_user')) {
             Schema::table('subject_user', function (Blueprint $table) {
                $table->dropColumn(['status', 'grade']);
             });
        }
    }
};
