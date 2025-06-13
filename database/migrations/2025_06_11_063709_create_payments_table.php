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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Foreign key ke examinations table
            $table->uuid('examination_id')->index();
            $table->foreign('examination_id')
                  ->references('id')
                  ->on('examinations')
                  ->onDelete('cascade');

            // Basic payment information
            $table->decimal('amount', 12, 2); // Increased precision for larger amounts
            $table->string('currency', 3)->default('IDR');
            $table->text('description')->nullable();

            // Payment method - sesuaikan dengan kebutuhan
            $table->enum('method', ['cash', 'qris', 'transfer', 'invoice', 'ewallet', 'retail_outlet', 'direct_debit', 'paylater', 'credit_card']);
            
            // Payment status - tambahkan status PENDING sesuai data Xendit
            $table->enum('status', [
                'pending', 
                'active', 
                'paid', 
                'failed', 
                'refunded', 
                'expired', 
                'cancelled', 
                'cancelled_by_user_change', 
                'pending_cash_payment'
            ])->default('pending');

            // Xendit integration fields
            $table->string('xendit_id')->nullable()->unique()->index(); // Xendit invoice ID
            $table->string('external_id')->nullable()->index(); // External ID for tracking
            $table->string('user_id')->nullable(); // Xendit user ID if needed
            $table->string('transaction_id')->nullable()->index(); // General transaction ID
            $table->string('reference_code')->nullable()->index(); // Internal reference code

            // Merchant information
            $table->string('merchant_name')->nullable();
            $table->text('merchant_profile_picture_url')->nullable();

            // Customer information
            $table->string('payer_email')->nullable();
            $table->string('customer_given_names')->nullable();
            $table->string('customer_mobile_number')->nullable();

            // Invoice/Payment Link fields
            $table->text('invoice_url')->nullable(); // Xendit checkout URL
            $table->text('checkout_link')->nullable(); // Alternative checkout link
            $table->string('success_redirect_url')->nullable();
            $table->string('failure_redirect_url')->nullable();
            $table->boolean('should_exclude_credit_card')->default(false);
            $table->boolean('should_send_email')->default(false);

            // Payment method availability flags (optional - bisa disimpan sebagai JSON juga)
            $table->json('available_banks')->nullable();
            $table->json('available_retail_outlets')->nullable();
            $table->json('available_ewallets')->nullable();
            $table->json('available_qr_codes')->nullable();
            $table->json('available_direct_debits')->nullable();
            $table->json('available_paylaters')->nullable();

            // QRIS specific fields
            $table->text('qr_string')->nullable();
            $table->text('qr_code_url')->nullable();

            // Virtual Account/Transfer specific fields
            $table->string('bank_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();

            // Additional fields
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // For storing additional data from Xendit
            
            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expiry_date')->nullable(); // Sesuai dengan field dari Xendit
            $table->timestamp('expiry_time')->nullable(); // Keep this for backward compatibility
            $table->timestamps(); // created_at, updated_at

            // Indexes for better performance
            $table->index(['status', 'created_at']);
            $table->index(['examination_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};