<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 50);
            $table->foreignId('service_request_id')->constrained('service_requests');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('callback_status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('callback_data')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'callback_status']);
            $table->foreign('student_id')->references('student_id')->on('students');
        });

        Schema::create('service_request_shipping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->cascadeOnDelete();
            $table->string('recipient_name');
            $table->text('address');
            $table->string('city');
            $table->string('country');
            $table->string('phone', 20);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->enum('status', ['pending', 'shipped', 'delivered'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_request_shipping');
        Schema::dropIfExists('payment_registrations');
    }
};
