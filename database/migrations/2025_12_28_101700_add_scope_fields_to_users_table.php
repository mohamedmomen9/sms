<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add university_id for University-scoped users
            $table->unsignedBigInteger('university_id')->nullable()->after('faculty_id');
            
            // Add subject_id for Subject-scoped users
            $table->unsignedBigInteger('subject_id')->nullable()->after('university_id');
            
            // Add is_admin boolean for quick role checks
            $table->boolean('is_admin')->default(false)->after('role');
            
            // Add foreign key constraints
            $table->foreign('university_id')->references('id')->on('universities')->onDelete('set null');
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('set null');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['subject_id']);
            $table->dropColumn(['university_id', 'subject_id', 'is_admin']);
        });
    }
};
