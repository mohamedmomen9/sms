<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plques_rec', function (Blueprint $table) {
            $table->id();
            $table->integer('ques_no');
            $table->string('aud_code');
            $table->string('plgrade_code');
            $table->string('stat');
            $table->integer('ans_no');
            $table->text('question');
            $table->string('opt_1')->nullable();
            $table->string('opt_2')->nullable();
            $table->string('opt_3')->nullable();
            $table->string('opt_4')->nullable();
            $table->string('test_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plques_rec');
    }
};
