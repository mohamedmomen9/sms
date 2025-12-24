<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cwtmp_rec', function (Blueprint $table) {
            $table->id();
            $table->string('crs_no');
            $table->string('sess');
            $table->integer('yr');
            $table->date('crdate');
            $table->string('creator');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cwtmp_rec');
    }
};
