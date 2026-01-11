<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create pivot table for curriculum-department many-to-many
        Schema::create('curriculum_department', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained('curricula')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['curriculum_id', 'department_id']);
        });

        // Migrate existing department_id data to pivot table
        $curricula = DB::table('curricula')->whereNotNull('department_id')->get();
        foreach ($curricula as $curriculum) {
            DB::table('curriculum_department')->insert([
                'curriculum_id' => $curriculum->id,
                'department_id' => $curriculum->department_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the old department_id column
        Schema::table('curricula', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }

    public function down(): void
    {
        // Re-add department_id column
        Schema::table('curricula', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
        });

        // Migrate data back (first department only)
        $pivotData = DB::table('curriculum_department')
            ->select('curriculum_id', DB::raw('MIN(department_id) as department_id'))
            ->groupBy('curriculum_id')
            ->get();
            
        foreach ($pivotData as $row) {
            DB::table('curricula')
                ->where('id', $row->curriculum_id)
                ->update(['department_id' => $row->department_id]);
        }

        Schema::dropIfExists('curriculum_department');
    }
};
