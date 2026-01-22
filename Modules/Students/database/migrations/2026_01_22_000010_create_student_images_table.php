<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('image_pose_center')->nullable();
            $table->string('image_pose_left')->nullable();
            $table->string('image_pose_right')->nullable();
            $table->string('image_pose_down')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->unique(['student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_images');
    }
};
