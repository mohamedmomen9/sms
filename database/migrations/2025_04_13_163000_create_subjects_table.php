<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('curriculum');  // Grouping for Curriculums
            $table->string('code');        
            $table->string('name_ar');     // Name in Arabic
            $table->string('name_en');     // Name in English
            $table->string('category')->nullable();    
            $table->string('type')->nullable();        
            $table->decimal('max_hours', 5, 2); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
