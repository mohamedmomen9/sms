<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cw_rec', function (Blueprint $table) {
            $table->id();
            $table->string('mscat');
            $table->string('sess');
            $table->integer('yr');
            $table->string('stat');
            $table->string('crs_no');
            $table->decimal('lclgrd', 5, 2)->nullable();
            $table->decimal('lclmidsess_grd', 5, 2)->nullable();
            $table->decimal('extragrd', 5, 2)->nullable();
            $table->decimal('downgrd', 5, 2)->nullable();
            $table->decimal('final_grd', 5, 2)->nullable();
            $table->date('beg_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cw_rec');
    }
};
