<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prog_enr_rec', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('prog');
            $table->string('major1');
            $table->string('mscat');
            $table->string('conc1');
            $table->string('entrtype');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prog_enr_rec');
    }
};
