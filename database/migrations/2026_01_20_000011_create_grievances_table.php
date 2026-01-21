<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grievances', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50);
            $table->foreignId('term_id')->constrained('terms');
            $table->string('violation_type');
            $table->text('violation_description')->nullable();
            $table->text('decision_text')->nullable();
            $table->date('dean_date')->nullable();
            $table->text('grievance_text')->nullable();
            $table->text('grievance_decision')->nullable();
            $table->date('grievance_dean_date')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('grievance_approval_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->timestamps();

            $table->index(['student_id', 'approval_status']);
            $table->foreign('student_id')->references('student_id')->on('students');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grievances');
    }
};
