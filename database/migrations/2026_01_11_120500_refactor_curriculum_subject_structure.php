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
        Schema::create('curriculum_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();

            $table->unique(['curriculum_id', 'subject_id']);
        });

        // Migrate existing data if needed
        // Since we are changing structure significantly, we assume data migration is either manual or handled elsewhere
        // But we can try to copy existing 1:N relations to the Pivot table.
        
        $results = \Illuminate\Support\Facades\DB::table('subjects')
            ->whereNotNull('curriculum_id')
            ->select('id', 'curriculum_id', 'is_mandatory')
            ->get();

        foreach ($results as $row) {
            \Illuminate\Support\Facades\DB::table('curriculum_subject')->insert([
                'curriculum_id' => $row->curriculum_id,
                'subject_id' => $row->id,
                'is_mandatory' => $row->is_mandatory ?? true, // use existing value if present (from migration done previously)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['curriculum_id']);
            $table->dropColumn('curriculum_id');
            $table->dropColumn('is_mandatory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('curriculum_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_mandatory')->default(true);
        });

        // Restore data logic is complex here (loss of data if multiple curricula were assigned)
        
        Schema::dropIfExists('curriculum_subject');
    }
};
