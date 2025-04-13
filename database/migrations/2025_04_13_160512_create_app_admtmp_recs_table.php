<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_admtmp_recs', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('prog');  // Program
            $table->string('plan_enr_sess'); // Plan Enrollment Session
            $table->string('major'); // Major
            $table->string('major2')->nullable(); // Secondary Major (can be null)
            $table->date('add_date'); // Add Date
            $table->date('upd_date'); // Update Date
            $table->string('campus'); // Campus
            $table->text('about')->nullable(); // About (potentially long text, nullable)
            $table->string('subprog')->nullable(); // Subprogram (nullable)
            $table->string('adm_by')->nullable(); // Admitted By (nullable)

            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_admtmp_recs');
    }
};
