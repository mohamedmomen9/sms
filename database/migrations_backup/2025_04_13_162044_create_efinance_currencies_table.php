<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('efinance_currency', function (Blueprint $table) {
            $table->string('currency_iso');
            $table->string('currency_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('efinance_currency');
    }
};
