<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_idtmp_recs', function (Blueprint $table) {
            $table->id();
            $table->string('app_idtmp_no');
            $table->string('addr_line3')->nullable();
            $table->string('fullname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('national');
            $table->string('phone');
            $table->string('Mobile');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_idtmp_recs');
    }
};
