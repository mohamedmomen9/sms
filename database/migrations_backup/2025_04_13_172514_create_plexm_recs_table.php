<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plexm_rec', function (Blueprint $table) {
            $table->id();
            $table->string('plexm_no');
            $table->string('plexm_name');
            $table->string('sco');
            $table->integer('days');
            $table->integer('yr');
            $table->string('sess');
            $table->string('stat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plexm_rec');
    }
};
