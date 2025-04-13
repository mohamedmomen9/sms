<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name');
            $table->string('field_slug')->unique();
            $table->string('field_namespace');
            $table->string('field_type');
            $table->json('field_data')->nullable(); // Use json for potentially complex data.
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_fields');
    }
};
