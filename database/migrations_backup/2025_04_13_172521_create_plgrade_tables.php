<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plgrade_table', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_no');
            $table->string('plgrade_code');
            $table->float('grade_mark');
            $table->string('stat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plgrade_table');
    }
};
