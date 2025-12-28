<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_rec', function (Blueprint $table) {
            $table->id();
            $table->integer('yr');
            $table->float('rate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_rec');
    }
};
