<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('default_ADfinance', function (Blueprint $table) {
            $table->id();
            $table->string('user_Rfid');
            $table->string('purpose');
            $table->string('purpose_iso');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('USD');
            $table->string('method');
            $table->string('Bank');
            $table->string('Bank_aria');
            $table->string('Bank_branch');
            $table->date('create_date');
            $table->date('modify_date')->nullable();
            $table->string('modify_by')->nullable();
            $table->string('status')->default('pending');
            $table->string('receipt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_ADfinance');
    }
};
