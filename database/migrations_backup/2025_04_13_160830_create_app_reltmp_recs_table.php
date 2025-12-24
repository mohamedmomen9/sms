<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_reltmp_recs', function (Blueprint $table) {
            $table->id();
            $table->string('rel_id');
            $table->string('rel');
            $table->string('rel_name');
            $table->string('suffix')->nullable();
            $table->string('phone');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_reltmp_recs');
    }
};
