<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_aattmp_recs', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('city');  // City
            $table->string('ctry'); // Country
            $table->string('strt'); // Street
            $table->string('line1'); // Line 1 of Address
            $table->string('line2')->nullable(); // Line 2 (optional)
            $table->string('line3')->nullable(); // Line 3 (optional)
            $table->string('zip'); // Zip Code

            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_aattmp_recs');
    }
};
