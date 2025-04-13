<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('salt');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('created_on');
            $table->timestamp('last_login')->nullable();
            $table->boolean('active')->default(false);
            $table->string('remember_code')->nullable();
            $table->string('forgotten_password_code')->nullable();
            $table->string('role')->nullable();
            $table->string('activation_code')->nullable();
            $table->string('lang')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('company')->nullable();
            $table->string('gender')->nullable();
            $table->string('website')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
