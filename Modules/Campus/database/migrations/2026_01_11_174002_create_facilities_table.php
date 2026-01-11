<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('room_facilities', function (Blueprint $table) {
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->primary(['room_id', 'facility_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_facilities');
        Schema::dropIfExists('facilities');
    }
};
