<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('div_table', function (Blueprint $table) {
            $table->id();
            $table->string('div');
            $table->string('txt');
            $table->string('divstatus');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('div_table');
    }
};
