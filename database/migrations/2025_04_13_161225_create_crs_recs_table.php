<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crs_rec', function (Blueprint $table) {
            $table->id();
            $table->string('crs_no');
            $table->string('title1');
            $table->string('mscat');
            $table->string('crstype');
            $table->string('crsctgry');
            $table->decimal('max_hrs', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crs_rec');
    }
};
