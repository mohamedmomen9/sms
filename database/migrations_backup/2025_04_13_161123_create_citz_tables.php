<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citz_table', function (Blueprint $table) {
            $table->id();
            $table->string('citz');
            $table->string('txt');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citz_table');
    }
};
