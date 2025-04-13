<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prog_table', function (Blueprint $table) {
            $table->string('prog')->primary();
            $table->string('txt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prog_table');
    }
};
