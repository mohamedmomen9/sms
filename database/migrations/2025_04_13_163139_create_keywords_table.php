<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('keyword_hash')->unique(); // Assuming keyword_hash should be unique.
            $table->string('module')->nullable();
            $table->string('entry_key')->nullable();
            $table->string('entry_plural')->nullable();
            $table->string('entry_id')->nullable();
            $table->string('uri')->nullable();
            $table->string('cp_edit_uri')->nullable();
            $table->string('cp_delete_uri')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
