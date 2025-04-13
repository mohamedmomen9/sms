<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('major_table', function (Blueprint $table) {
            $table->id();
            $table->string('major'); // Major code
            $table->string('sco'); // School Code
            $table->string('txt'); // Major Name
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('major_table');
    }
};
