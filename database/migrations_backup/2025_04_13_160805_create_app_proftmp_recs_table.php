<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_proftmp_recs', function (Blueprint $table) {
            $table->id();
            $table->string('citz');
            $table->string('sex');
            $table->date('birth_date');
            $table->string('birthplace_city');
            $table->string('tel2');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_proftmp_recs');
    }
};
