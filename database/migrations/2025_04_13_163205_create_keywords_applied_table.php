<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywords_applied', function (Blueprint $table) {
            $table->string('hash');
            $table->unsignedBigInteger('keyword_id');

            $table->primary(['hash', 'keyword_id']);

            $table->foreign('keyword_id')->references('id')->on('keywords')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords_applied');
    }
};
