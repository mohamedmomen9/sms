<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            // university_id removed
            $table->string('code');
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['code']);
        });

        // Add campus_id to faculties table
        Schema::table('faculties', function (Blueprint $table) {
            $table->foreignId('campus_id')->nullable()
                ->constrained('campuses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
            $table->dropColumn('campus_id');
        });

        Schema::dropIfExists('campuses');
    }
};
