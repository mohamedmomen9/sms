<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id')->nullable(); // Assuming category_id is a foreign key.  Adjust if needed.
            $table->unsignedBigInteger('author_id')->nullable(); // Assuming author_id is a foreign key. Adjust if needed.
            $table->timestamp('created_on');
            $table->timestamp('updated_on');
            $table->string('status')->default('draft');
            $table->text('keywords')->nullable();
            $table->longText('body'); // Use longText for potentially large blog content.
            $table->longText('intro'); // Use longText for the introduction.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
