<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('agreeable_type');
            $table->unsignedBigInteger('agreeable_id');
            $table->string('agreement_type');
            $table->timestamp('accepted_at');
            $table->timestamps();

            $table->unique(['agreeable_type', 'agreeable_id', 'agreement_type'], 'user_agreement_unique');
            $table->index(['agreement_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_agreements');
    }
};
