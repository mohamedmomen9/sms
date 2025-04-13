<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_admtmp_recs', function (Blueprint $table) {
            $table->id();
            $table->string('prog');
            $table->string('plan_enr_sess');
            $table->string('major');
            $table->string('major2')->nullable();
            $table->date('add_date');
            $table->date('upd_date');
            $table->string('campus');
            $table->text('about')->nullable();
            $table->string('subprog')->nullable();
            $table->string('adm_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_admtmp_recs');
    }
};
