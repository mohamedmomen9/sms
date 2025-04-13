<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reid', function (Blueprint $table) {
            $table->id();
            $table->string('tickid');
            $table->string('purpose')->nullable();
            $table->integer('prntcount')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reid');
    }
};
