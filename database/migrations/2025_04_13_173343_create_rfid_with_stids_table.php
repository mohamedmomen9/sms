<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfid_with_stid', function (Blueprint $table) {
            $table->id();
            $table->string('rfid');
            $table->unsignedBigInteger('stid');
            $table->string('stname');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_with_stid');
    }
};
