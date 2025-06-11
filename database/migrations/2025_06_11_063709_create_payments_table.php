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

            // Gunakan uuid() untuk foreign key ke examinations
            $table->uuid('examination_id')->index(); // Tambahkan index untuk FK
            $table->foreign('examination_id')
                  ->references('id')
                  ->on('examinations')
                  ->onDelete('cascade');

            $table->decimal('amount', 10, 2);
            // Ubah nama kolom dari payment_method menjadi method agar konsisten dengan controller
            $table->enum('method', ['cash', 'qris', 'transfer']); 
            // Tambahkan 'active' ke enum status
            $table->enum('status', ['pending', 'active', 'paid', 'failed', 'refunded', 'expired'])->default('pending');
            
            // Kolom baru dari integrasi Xendit dan kebutuhan lainnya
            $table->string('xendit_id')->nullable()->index(); // ID transaksi dari Xendit
            $table->string('currency', 3)->default('IDR'); // Mata uang pembayaran
            $table->string('transaction_id')->nullable()->index(); // ID transaksi umum (bisa juga dari Xendit)
            $table->string('reference_code')->nullable()->index(); // Kode referensi internal atau external_id Xendit

            // Untuk QRIS
            $table->text('qr_string')->nullable();
            $table->text('qr_code_url')->nullable();

            // Untuk Virtual Account Transfer
            $table->string('bank_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();

            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expiry_time')->nullable(); // Waktu kadaluarsa pembayaran

            $table->timestamps();
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