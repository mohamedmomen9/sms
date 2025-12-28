<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_aattmp_recs', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('ctry');
            $table->string('strt');
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('line3')->nullable();
            $table->string('zip');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_aattmp_recs');
    }
};
