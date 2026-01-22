<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected'])->default('pending');
            $table->json('application_data')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
