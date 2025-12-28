<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_types', function (Blueprint $table) {
            $table->id();
            $table->string('stream_id');
            $table->text('body')->nullable();
            $table->boolean('save_as_files')->default(false);
            $table->string('slug');
            $table->string('title');
            $table->string('js')->nullable();
            $table->string('css')->nullable();
            $table->timestamp('updated_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_types');
    }
};
