<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_chunks', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('class')->nullable();
            $table->string('page_id')->nullable();
            $table->text('body');
            $table->boolean('parsed')->default(false);
            $table->string('type')->nullable();
            $table->integer('sort')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_chunks');
    }
};
