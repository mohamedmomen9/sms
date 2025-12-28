<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Add faculty_id to subjects for direct Faculty relationship
            // This allows subjects to optionally belong to a faculty without requiring a department
            $table->unsignedBigInteger('faculty_id')->nullable()->after('department_id');
            
            // Make department_id nullable since subjects can belong to Faculty directly
            $table->unsignedBigInteger('department_id')->nullable()->change();
            
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropColumn('faculty_id');
        });
    }
};
