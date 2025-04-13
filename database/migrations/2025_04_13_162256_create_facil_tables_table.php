<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facil_table', function (Blueprint $table) {
            $table->id();
            $table->string('campus');
            $table->string('bldg');
            $table->string('room');
            $table->string('descr')->nullable();
            $table->integer('max_occ');
            $table->integer('max_exm_occ');
            $table->integer('base_occ');
            $table->string('ctgry');
            $table->string('phone_ext')->nullable();
            $table->string('phone')->nullable();
            $table->string('dept')->nullable();
            $table->string('stat');
            $table->string('exm_stat')->nullable();
            $table->string('site')->nullable();
            $table->boolean('on_campus')->default(false); // Assuming boolean value.
            $table->date('active_date')->nullable();
            $table->date('inactive_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facil_table');
    }
};
