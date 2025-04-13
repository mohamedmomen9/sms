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
        Schema::create('acad_cal_recs', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->integer('yr');  // Year
            $table->string('sess'); // Session
            $table->boolean('crsess')->default(false); // Current Session Indicator, default false
            $table->string('prog'); // Program

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acad_cal_recs');
    }
};
