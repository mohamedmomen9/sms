<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('widget_areas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widget_areas');
    }
};
