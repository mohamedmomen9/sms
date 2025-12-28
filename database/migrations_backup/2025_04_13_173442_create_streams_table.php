<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->string('stream_slug');
            $table->string('stream_namespace');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('streams');
    }
};
