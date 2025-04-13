<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crsreq_rec', function (Blueprint $table) {
            $table->id();
            $table->string('crsreq_crs_no');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crsreq_rec');
    }
};
