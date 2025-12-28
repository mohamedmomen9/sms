<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Onlnperm_rec', function (Blueprint $table) {
            $table->id();
            $table->string('serial_n');
            $table->string('inst_id');
            $table->string('prog');
            $table->string('sco');
            $table->string('major');
            $table->string('conc');
            $table->integer('yr1');
            $table->integer('yr2');
            $table->string('sess');
            $table->integer('yr');
            $table->string('mscat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Onlnperm_rec');
    }
};
