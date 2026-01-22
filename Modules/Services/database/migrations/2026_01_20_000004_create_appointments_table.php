<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50);
            $table->foreignId('term_id')->constrained('terms');
            $table->foreignId('department_id')->constrained('appointment_departments');
            $table->foreignId('purpose_id')->constrained('appointment_purposes');
            $table->foreignId('slot_id')->constrained('appointment_slots');
            $table->date('appointment_date');
            $table->string('phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['booked', 'completed', 'cancelled', 'no_show'])->default('booked');
            $table->string('language', 5)->default('ar');
            $table->timestamps();

            $table->index(['student_id', 'appointment_date']);
            $table->index(['department_id', 'appointment_date', 'slot_id']);
            $table->foreign('student_id')->references('student_id')->on('students');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
