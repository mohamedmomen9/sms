<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('full_students_data_with_rfid', function (Blueprint $table) {
            $table->string('cicid'); // Assuming cicid is the primary key or a unique identifier.
            $table->string('std_name');
            $table->string('instructorid')->nullable();
            $table->string('campus');
            $table->string('bldg');
            $table->string('room');
            $table->string('crs_no');
            $table->string('title1');
            $table->string('days');
            $table->time('beg_tm'); // Use time for start and end times.
            $table->time('end_tm');
            $table->string('im')->nullable();
            $table->string('mtg_no')->nullable();

            // Add primary key constraint (if cicid is the unique identifier)
            $table->primary('cicid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('full_students_data_with_rfid');
    }
};
