<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans_rec', function (Blueprint $table) {
            $table->id();
            $table->integer('ans_no');
            $table->string('student_id');
            $table->string('sess');
            $table->integer('yr');
            $table->integer('ques_no');
            $table->text('ans');
            $table->integer('mark');
            $table->timestamp('beg_tm')->nullable();
            $table->string('result')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans_rec');
    }
};
