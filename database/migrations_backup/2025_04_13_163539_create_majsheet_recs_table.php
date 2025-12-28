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
            $table->string('prog');
            $table->string('major');
            $table->string('conc');
            $table->string('mscat');
            $table->string('crs_no');
            $table->string('stat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('majsheet_rec');
    }
};
