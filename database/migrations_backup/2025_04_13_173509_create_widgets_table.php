<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('website')->nullable();
            $table->string('version')->nullable();
            $table->boolean('enabled')->default(true);
            $table->integer('order')->nullable();
            $table->timestamp('updated_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widgets');
    }
};
