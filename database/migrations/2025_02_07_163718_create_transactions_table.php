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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('donation_id')->nullable()->constrained('donations', 'id')->nullOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->unsignedInteger('anonymous_donor_id')->nullable();
            $table->foreign('anonymous_donor_id')->references('id')->on('anonymous_donors')->nullOnDelete();
            $table->string('midtrans_transaction_id')->unique()->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('payment_type')->nullable();
            $table->enum('transaction_status', [
                'pending',
                'success',
                'failed',
                'expired',
                'canceled',
                'refunded',
                'partially_refunded',
                'chargeback'
            ])->default('pending');
            $table->string('message')->nullable();
            $table->boolean('is_anonym')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
