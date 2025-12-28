<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('uri')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('page_types')->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('css')->nullable();
            $table->string('js')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('meta_robots_no_index')->default(false);
            $table->boolean('meta_robots_no_follow')->default(false);
            $table->text('meta_description')->nullable();
            $table->boolean('rss_enabled')->default(false);
            $table->boolean('comments_enabled')->default(false);
            $table->string('status')->nullable();
            $table->timestamp('created_on');
            $table->timestamp('updated_on');
            $table->string('restricted_to')->nullable();
            $table->boolean('strict_uri')->default(false);
            $table->boolean('is_home')->default(false);
            $table->integer('order')->nullable();
            $table->string('entry_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
