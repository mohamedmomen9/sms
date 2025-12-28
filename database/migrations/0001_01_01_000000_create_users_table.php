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
            $table->string('salt')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('created_on')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->boolean('active')->default(false);
            $table->string('remember_code')->nullable();
            $table->string('forgotten_password_code')->nullable();
            // university_id removed for single-tenant app
            $table->unsignedBigInteger('faculty_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->string('activation_code')->nullable();
            $table->string('lang')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('company')->nullable();
            $table->string('gender')->nullable();
            $table->string('website')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
