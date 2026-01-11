<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('buildings')->cascadeOnDelete();
            $table->string('room_code')->unique();
            $table->string('number');
            $table->string('name')->nullable();
            $table->integer('floor_number');
            $table->enum('type', ['classroom', 'lab', 'auditorium', 'office'])->default('classroom');
            $table->integer('capacity')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
