<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stdreceipt_rec', function (Blueprint $table) {
            $table->id();
            $table->string('stdreceipt_no');
            $table->unsignedBigInteger('student_id');
            $table->string('sess');
            $table->integer('yr');
            $table->float('amount');
            $table->string('currency');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stdreceipt_rec');
    }
};
