<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // Assuming slugs should be unique.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_folders');
    }
};
