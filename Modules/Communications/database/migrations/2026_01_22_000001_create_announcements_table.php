<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('image', 500)->nullable();
            $table->string('cropped_image', 500)->nullable();
            $table->string('title');
            $table->text('details')->nullable();
            $table->foreignId('campus_id')->nullable()->constrained('campuses')->nullOnDelete();
            $table->string('link')->nullable();
            $table->date('date')->default(now());
            $table->enum('type', ['news', 'events', 'lectures', 'announcements'])->default('news');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['campus_id', 'type']);
            $table->index(['date', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
