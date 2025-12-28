<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reg_rec', function (Blueprint $table) {
            $table->id();
            $table->integer('cw_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_rec');
    }
};
