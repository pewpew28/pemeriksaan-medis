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
        Schema::create('examinations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Kolom Foreign Key ke patients
            $table->foreignId('patient_id')
                  ->constrained('patients') // Menghubungkan ke tabel 'patients'
                  ->onDelete('cascade');    // Jika pasien dihapus, pemeriksaan juga dihapus

            // Detail Jadwal Pemeriksaan
            $table->date('scheduled_date')->nullable(); // Nullable jika belum dijadwalkan
            $table->time('scheduled_time')->nullable(); // Nullable jika belum dijadwalkan

            // Detail Penjemputan (Opsional)
            $table->boolean('pickup_requested')->default(false);
            $table->string('pickup_address')->nullable();
            $table->string('pickup_location_map')->nullable(); // URL peta, opsional
            $table->time('pickup_time')->nullable();

            // Status Pemeriksaan (Diperluas untuk mencakup status pembayaran)
            // 'created': permintaan baru yang belum direspon/diproses
            // 'pending_payment': menunggu pembayaran QRIS/VA
            // 'pending_cash_payment': menunggu pembayaran tunai di klinik
            // 'paid': pembayaran sudah lunas
            // 'expired_payment': pembayaran kadaluarsa
            // 'scheduled': sudah dijadwalkan/dikonfirmasi
            // 'in_progress': pemeriksaan sedang berjalan
            // 'completed': pemeriksaan sudah selesai
            // 'cancelled': dibatalkan
            $table->enum('status', [
                'created', // Initial state when examination is first recorded
                'pending_payment',
                'pending_cash_payment',
                'paid',
                'expired_payment',
                'scheduled',
                'in_progress', // New state for when examination is actually happening
                'completed',
                'cancelled'
            ])->default('created'); // Default status yang lebih sesuai

            // Catatan Tambahan
            $table->text('notes')->nullable();

            // Status Hasil Pemeriksaan
            $table->boolean('result_available')->default(false); // True jika hasil sudah siap diunduh

            // Detail Pembayaran (Jika ingin memisahkan status pembayaran dari status umum pemeriksaan)
            // 'pending': belum dibayar
            // 'paid': sudah dibayar
            // 'failed': pembayaran gagal (opsional, jika ada integrasi gateway)
            // Perhatikan bahwa status 'paid' di sini bisa sinkron dengan status 'paid' di kolom 'status' utama
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_method')->nullable(); // Contoh: Bank Transfer, Credit Card, Cash

            // Harga Final Pemeriksaan (Ini akan menyimpan service_item_price dari frontend)
            $table->decimal('final_price', 10, 2)->default(0.00);

            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};