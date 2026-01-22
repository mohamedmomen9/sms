<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['course', 'sdo', 'field_training'])->default('course');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('assessment_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('assessment_categories')->cascadeOnDelete();
            $table->text('question');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('assessment_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->string('name');
            $table->integer('weight');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_rates');
        Schema::dropIfExists('assessment_questions');
        Schema::dropIfExists('assessment_categories');
        Schema::dropIfExists('assessments');
    }
};
