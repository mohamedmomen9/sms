<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plaud_rec', function (Blueprint $table) {
            $table->id();
            $table->string('aud_code');
            $table->integer('aud_no');
            $table->string('aud_file');
            $table->string('stat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plaud_rec');
    }
};
