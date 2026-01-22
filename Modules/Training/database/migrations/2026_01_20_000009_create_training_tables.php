<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->text('description')->nullable();
            $table->foreignId('faculty_id')->nullable()->constrained('faculties');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('concentration')->nullable();
            $table->string('cohort')->nullable();
            $table->integer('capacity')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_available')->default(true);
            $table->json('conditions')->nullable();
            $table->json('required_documents')->nullable();
            $table->timestamps();
        });

        Schema::create('field_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50);
            $table->foreignId('opportunity_id')->constrained('training_opportunities');
            $table->foreignId('term_id')->constrained('terms');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('supervisor_notes')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('student_id')->on('students');
        });

        Schema::create('student_wishlists', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50);
            $table->foreignId('term_id')->constrained('terms');
            $table->json('item_ids');
            $table->json('item_names')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'term_id']);
            $table->foreign('student_id')->references('student_id')->on('students');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_wishlists');
        Schema::dropIfExists('field_trainings');
        Schema::dropIfExists('training_opportunities');
    }
};
