<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stu_hist_rec', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->integer('rem_crs');
            $table->float('gpa');
            $table->string('mscat');
            $table->string('sess');
            $table->integer('yr');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stu_hist_rec');
    }
};
