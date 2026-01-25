<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->id();
            $table->enum('platform', ['ios', 'android']);
            $table->string('version');
            $table->string('min_version')->nullable();
            $table->boolean('force_update')->default(false);
            $table->text('release_notes')->nullable();
            $table->timestamps();

            $table->unique(['platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_versions');
    }
};
