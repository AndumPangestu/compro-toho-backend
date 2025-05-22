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
        Schema::create('article_categories', function (Blueprint $table) {
            $table->unsignedInteger('id', true)->primary();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->enum('type', ['news', 'kindness_story', 'release', 'infographics']);
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('article_categories')->nullOnDelete();
            $table->foreignUuid('donation_id')->nullable()->constrained('donations', 'id')->nullOnDelete();
            $table->string('description')->nullable();
            $table->text('content')->nullable();
            $table->boolean('put_on_highlight')->default(false);
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_categories');
        Schema::dropIfExists('articles');
    }
};
