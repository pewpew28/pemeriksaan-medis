<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceItem;

class ServiceItemsSeeder extends Seeder
{
    public function run(): void
    {
        ServiceItem::firstOrCreate(
            ['name' => 'Cek Darah Lengkap'],
            ['description' => 'Pemeriksaan darah lengkap untuk kondisi umum.', 'price' => 150000.00]
        );
        ServiceItem::firstOrCreate(
            ['name' => 'Rontgen Dada'],
            ['description' => 'Pencitraan dada untuk melihat kondisi paru-paru dan jantung.', 'price' => 200000.00]
        );
        ServiceItem::firstOrCreate(
            ['name' => 'USG Perut'],
            ['description' => 'Pemeriksaan USG untuk organ dalam perut.', 'price' => 250000.00]
        );
        ServiceItem::firstOrCreate(
            ['name' => 'Pemeriksaan Gigi'],
            ['description' => 'Pemeriksaan rutin gigi dan mulut oleh dokter gigi.', 'price' => 100000.00]
        );
        ServiceItem::firstOrCreate(
            ['name' => 'Vaksinasi Influenza'],
            ['description' => 'Vaksinasi untuk mencegah flu musiman.', 'price' => 120000.00]
        );
        ServiceItem::firstOrCreate(
            ['name' => 'Konsultasi Dokter Umum'],
            ['description' => 'Konsultasi tatap muka dengan dokter umum.', 'price' => 80000.00]
        );
        ServiceItem::firstOrCreate(
            ['name' => 'Tes Urin Lengkap'],
            ['description' => 'Analisis urin untuk mendeteksi berbagai kondisi kesehatan.', 'price' => 90000.00]
        );
        // Tambahkan jenis layanan lainnya
    }
}