<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_streams', function (Blueprint $table) {
            $table->id();
            $table->string('stream_slug')->unique();
            $table->string('stream_namespace');
            $table->string('title_column');
            $table->json('view_options')->nullable(); // Use json for potentially complex view options.
            $table->string('stream_prefix')->nullable();
            $table->text('sorting')->nullable();  // Could be JSON or a string representing sorting rules.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_streams');
    }
};
