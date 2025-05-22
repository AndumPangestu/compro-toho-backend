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
        Schema::create('donation_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('donation_categories')->nullOnDelete();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('fund_usage_details')->nullable();
            $table->text('description')->nullable();
            $table->text('distribution_information')->nullable();
            $table->unsignedBigInteger('target_amount');
            $table->unsignedBigInteger('collected_amount')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location')->nullable();
            $table->boolean('put_on_highlight')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_categories');
        Schema::dropIfExists('donations');
    }
};
