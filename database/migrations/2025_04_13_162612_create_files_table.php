<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_id')->nullable(); // Assuming folder_id is a foreign key. Adjust if needed.
            $table->string('name');
            $table->string('path');
            $table->string('type')->nullable();
            $table->integer('size')->nullable();
            $table->timestamp('created_on');

            // Foreign Key Constraint (Optional, but recommended)
            $table->foreign('folder_id')->references('id')->on('folders')->nullable()->cascadeOnDelete(); // Assuming 'folders' table. Adjust if needed.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
