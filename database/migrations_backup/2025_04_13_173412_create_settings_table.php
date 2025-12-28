<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('default')->nullable();
            $table->string('value')->nullable();
            $table->text('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_gui')->default(false);
            $table->string('module')->nullable();
            $table->integer('order')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
