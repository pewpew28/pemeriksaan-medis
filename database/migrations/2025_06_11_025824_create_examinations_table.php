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

            // Kolom Foreign Key
            $table->foreignId('patient_id')
                  ->constrained('patients') // Menghubungkan ke tabel 'patients'
                  ->onDelete('cascade');    // Jika pasien dihapus, pemeriksaan juga dihapus

            // Detail Jadwal Pemeriksaan
            $table->date('scheduled_date');
            $table->time('scheduled_time');

            // Detail Penjemputan (Opsional)
            $table->boolean('pickup_requested')->default(false);
            $table->string('pickup_address')->nullable();
            $table->string('pickup_location_map')->nullable(); // URL peta, opsional
            $table->time('pickup_time')->nullable();

            // Status Pemeriksaan
            // pending: permintaan baru
            // scheduled: sudah dijadwalkan/dikonfirmasi
            // completed: pemeriksaan sudah selesai
            // cancelled: dibatalkan
            $table->enum('status', ['pending', 'scheduled', 'completed', 'cancelled'])->default('pending');

            // Catatan Tambahan
            $table->text('notes')->nullable();

            // Status Hasil Pemeriksaan
            $table->boolean('result_available')->default(false); // True jika hasil sudah siap diunduh

            // Detail Pembayaran
            // pending: belum dibayar
            // paid: sudah dibayar
            // failed: pembayaran gagal (opsional, jika ada integrasi gateway)
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_method')->nullable(); // Contoh: Bank Transfer, Credit Card, Cash

            // Harga Final Pemeriksaan
            // Menyimpan harga final saat pemeriksaan dibuat/dikonfirmasi.
            // Penting agar harga tidak berubah meskipun harga di service_items berubah.
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