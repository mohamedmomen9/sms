<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campus_table', function (Blueprint $table) {
            $table->id();
            $table->string('campcat');
            $table->string('txt');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_table');
    }
};
