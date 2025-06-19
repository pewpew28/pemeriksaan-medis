<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel service_categories terlebih dahulu
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama kategori (contoh: Hematologi, Fungsi Ginjal, dll)
            $table->string('code', 10)->unique()->nullable(); // Kode kategori (opsional)
            $table->text('description')->nullable(); // Deskripsi kategori
            $table->integer('sort_order')->default(0); // Urutan tampilan
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });

        // Buat tabel service_items dengan relasi ke categories
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('service_categories')->onDelete('cascade');
            $table->string('name'); // Nama jenis pemeriksaan
            $table->string('code', 20)->nullable(); // Kode pemeriksaan (opsional)
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Harga pemeriksaan
            $table->string('unit')->nullable(); // Satuan hasil (mg/dL, %, dll)
            $table->text('normal_range')->nullable(); // Range normal
            $table->integer('sort_order')->default(0); // Urutan dalam kategori
            $table->boolean('is_active')->default(true); // Apakah layanan ini aktif
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['category_id', 'is_active']);
            $table->index('name');
        });

        // Update tabel examinations untuk menggunakan service_item_id
        Schema::table('examinations', function (Blueprint $table) {
            // Jika sudah ada kolom examination_type, bisa di-comment atau di-drop
            // $table->dropColumn('examination_type'); // Uncomment jika ingin mengganti sepenuhnya
            
            $table->foreignId('service_item_id')->nullable()->constrained('service_items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_item_id');
            // $table->string('examination_type')->nullable(); // Uncomment jika dikembalikan
        });
        
        Schema::dropIfExists('service_items');
        Schema::dropIfExists('service_categories');
    }
};