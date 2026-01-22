<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('image', 500);
            $table->string('title');
            $table->text('details')->nullable();
            $table->foreignId('campus_id')->nullable()->constrained('campuses')->nullOnDelete();
            $table->string('link')->nullable();
            $table->date('date')->default(now());
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['campus_id', 'is_active']);
            $table->index(['date', 'is_active']);
        });

        Schema::create('offer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers')->cascadeOnDelete();
            $table->enum('entity_type', ['student', 'teacher', 'parent']);
            $table->unsignedBigInteger('entity_id');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();

            $table->unique(['offer_id', 'entity_type', 'entity_id']);
            $table->index(['entity_type', 'entity_id', 'is_favorite']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_logs');
        Schema::dropIfExists('offers');
    }
};
