<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama jenis pemeriksaan
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Harga pemeriksaan
            $table->boolean('is_active')->default(true); // Apakah layanan ini aktif
            $table->timestamps();
        });

        // Opsional: Tambahkan foreign key examination_type_id ke tabel examinations
        Schema::table('examinations', function (Blueprint $table) {
            // Pastikan kolom examination_type yang lama dihapus atau diubah namanya jika sudah ada
            // $table->dropColumn('examination_type'); // Jika ingin mengganti sepenuhnya
            $table->foreignId('service_item_id')->nullable()->constrained('service_items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_item_id');
            // $table->string('examination_type')->nullable(); // Jika dikembalikan
        });
        Schema::dropIfExists('service_items');
    }
};