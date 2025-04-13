<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majsheet_rec', function (Blueprint $table) {
            $table->id();
            $table->string('prog'); // Program
            $table->string('major');  // Major
            $table->string('conc'); // Concentration
            $table->string('mscat'); // Category
            $table->string('crs_no'); // Course number
            $table->string('stat'); // Status
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('majsheet_rec');
    }
};
