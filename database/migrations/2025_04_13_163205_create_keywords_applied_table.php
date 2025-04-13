<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywords_applied', function (Blueprint $table) {
            $table->string('hash');
            $table->unsignedBigInteger('keyword_id');

            // Composite Primary Key - Ensures uniqueness of the combination.  Important for this type of table.
            $table->primary(['hash', 'keyword_id']);

            // Foreign Key Constraint (Assuming keywords table exists)
            $table->foreign('keyword_id')->references('id')->on('keywords')->cascadeOnDelete()->cascadeOnUpdate(); // Adjust if the table name is different.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords_applied');
    }
};
