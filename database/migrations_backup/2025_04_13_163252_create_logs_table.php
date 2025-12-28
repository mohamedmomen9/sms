<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('cardNumer');
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->integer('hour');
            $table->integer('min');
            $table->timestamp('full_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
