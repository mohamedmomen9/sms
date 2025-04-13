<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plapp_rec', function (Blueprint $table) {
            $table->id();
            $table->string('appid');
            $table->string('test_id');
            $table->string('sco');
            $table->date('test_date');
            $table->string('stat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plapp_rec');
    }
};
