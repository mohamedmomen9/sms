<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disc_table', function (Blueprint $table) {
            $table->id();
            $table->string('disc_no');
            $table->string('txt');
            $table->decimal('disc_val', 10, 2); // Adjust precision/scale as needed.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disc_table');
    }
};
