<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stdacc_rec', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->float('eg_balance');
            $table->float('ca_balance');
            $table->string('sess');
            $table->integer('yr');
            $table->string('creator');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stdacc_rec');
    }
};
