<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('id_rec', function (Blueprint $table) {
            $table->id();
            $table->string('ss_no')->unique()->nullable(); // Assuming SS number should be unique.  Consider removing if not required.
            $table->string('fullname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->date('birth_date');
            $table->string('sex');
            $table->string('citz');
            $table->string('addr_line1');
            $table->string('addr_line2')->nullable();
            $table->string('st');
            $table->string('ctry');
            $table->string('phone')->nullable();
            $table->string('cicemail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('id_rec');
    }
};
