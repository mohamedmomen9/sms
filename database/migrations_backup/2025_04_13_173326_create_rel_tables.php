<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rel_table', function (Blueprint $table) {
            $table->id();
            $table->string('rel');
            $table->text('txt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rel_table');
    }
};
