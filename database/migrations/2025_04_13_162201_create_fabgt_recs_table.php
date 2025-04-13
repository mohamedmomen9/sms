<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fabgt_rec', function (Blueprint $table) {
            $table->id();
            $table->string('fabgt_no');
            $table->decimal('amt1', 15, 2); // Adjust precision/scale as needed.  Large amounts might require more digits.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fabgt_rec');
    }
};
