<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50);
            $table->foreignId('term_id')->constrained('terms');
            $table->foreignId('service_type_id')->constrained('service_types');
            $table->text('notes')->nullable();
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled', 'delivered'])->default('pending');
            $table->boolean('shipping_required')->default(false);
            $table->string('directed_to')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'term_id']);
            $table->index(['status', 'created_at']);
            $table->foreign('student_id')->references('student_id')->on('students');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
