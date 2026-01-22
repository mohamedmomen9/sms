<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('url');
            $table->boolean('active')->default(true);
            $table->enum('target_type', ['ALL', 'STUDENT', 'TEACHER', 'PARENT'])->default('ALL');
            $table->foreignId('campus_id')->nullable()->constrained('campuses')->nullOnDelete();
            $table->timestamps();

            $table->index(['active', 'target_type']);
        });

        Schema::create('survey_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->string('participant_type');
            $table->unsignedBigInteger('participant_id');
            $table->boolean('status')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['survey_id', 'participant_type', 'participant_id'], 'survey_participant_unique');
            $table->index(['participant_type', 'participant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_logs');
        Schema::dropIfExists('surveys');
    }
};
