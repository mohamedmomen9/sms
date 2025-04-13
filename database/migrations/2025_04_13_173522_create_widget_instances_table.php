<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('widget_instances', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('widget_id');
            $table->unsignedBigInteger('widget_area_id');
            $table->text('options')->nullable();
            $table->integer('order')->nullable();
            $table->timestamp('created_on');
            $table->timestamp('updated_on');

            $table->foreign('widget_id')->references('id')->on('widgets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('widget_area_id')->references('id')->on('widget_areas')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widget_instances');
    }
};
