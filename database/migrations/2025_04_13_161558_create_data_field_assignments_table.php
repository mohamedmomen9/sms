<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_field_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stream_id'); // Assuming stream_id is a foreign key. Adjust if needed.
            $table->unsignedBigInteger('field_id'); // Assuming field_id is a foreign key. Adjust if needed.
            $table->text('instructions')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->integer('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_field_assignments');
    }
};
