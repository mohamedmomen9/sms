<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('curriculum_subject', function (Blueprint $table) {
            $table->boolean('uses_gpa')->default(false);
            $table->decimal('gpa_requirement', 4, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('curriculum_subject', function (Blueprint $table) {
            $table->dropColumn(['uses_gpa', 'gpa_requirement']);
        });
    }
};
