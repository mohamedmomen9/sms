<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add translatable name columns to campuses
        if (Schema::hasTable('campuses')) {
            Schema::table('campuses', function (Blueprint $table) {
                if (!Schema::hasColumn('campuses', 'name_en')) {
                    $table->string('name_en')->nullable()->after('code');
                    $table->string('name_ar')->nullable()->after('name_en');
                }
            });
            
            // Migrate existing name data to name_en
            DB::table('campuses')->whereNull('name_en')->update([
                'name_en' => DB::raw('name'),
            ]);
        }

        // Add translatable name columns to faculties
        if (Schema::hasTable('faculties')) {
            Schema::table('faculties', function (Blueprint $table) {
                 if (!Schema::hasColumn('faculties', 'name_en')) {
                    $table->string('name_en')->nullable()->after('code');
                    $table->string('name_ar')->nullable()->after('name_en');
                 }
            });
            
            // Migrate existing name data to name_en
            DB::table('faculties')->whereNull('name_en')->update([
                'name_en' => DB::raw('name'),
            ]);
        }

        // Add translatable name columns to departments
        if (Schema::hasTable('departments')) {
            Schema::table('departments', function (Blueprint $table) {
                if (!Schema::hasColumn('departments', 'name_en')) {
                    $table->string('name_en')->nullable()->after('code');
                    $table->string('name_ar')->nullable()->after('name_en');
                }
            });
            
            // Migrate existing name data to name_en
            DB::table('departments')->whereNull('name_en')->update([
                'name_en' => DB::raw('name'),
            ]);
        }

        // Add translatable name columns to curricula
        if (Schema::hasTable('curricula')) {
            Schema::table('curricula', function (Blueprint $table) {
                if (!Schema::hasColumn('curricula', 'name_en')) {
                    $table->string('name_en')->nullable()->after('department_id');
                    $table->string('name_ar')->nullable()->after('name_en');
                }
            });
            
            // Migrate existing name data to name_en
            DB::table('curricula')->whereNull('name_en')->update([
                'name_en' => DB::raw('name'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campuses', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });

        Schema::table('faculties', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });

        Schema::table('curricula', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_ar']);
        });
    }
};
