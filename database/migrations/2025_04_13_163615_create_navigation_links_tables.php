<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_links', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('link_type')->nullable();
            $table->string('url')->nullable();
            $table->string('uri')->nullable();
            $table->string('module_name')->nullable();
            $table->string('page_id')->nullable();
            $table->integer('position')->nullable();
            $table->string('target')->nullable();
            $table->string('class')->nullable();
            $table->unsignedBigInteger('navigation_group_id')->nullable();
            $table->foreign('navigation_group_id')->references('id')->on('navigation_groups')->nullable()->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_links');
    }
};
