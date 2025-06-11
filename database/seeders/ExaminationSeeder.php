<?php

namespace Database\Seeders;

use App\Models\Examination;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class ExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            // Buat 1-3 pemeriksaan dengan status acak dari factory default
            Examination::factory()
                ->count(rand(1, 3))
                ->for($patient)
                ->create();
        }

        // Buat tambahan 5 pemeriksaan yang selesai (completed) untuk keperluan testing hasil
        Examination::factory()
            ->count(5)
            ->completed()
            ->create();

        // Jika ingin menambahkan file hasil (PDF dummy), bisa ditambahkan di sini
        // asalkan kamu sudah setup MediaLibrary (Spatie) dengan collection 'results'.
        /*
        Examination::completed()->get()->each(function ($examination) {
            $examination->addMedia(database_path('seed_files/dummy_result.pdf'))
                        ->preservingOriginal()
                        ->toMediaCollection('results');
        });
        */
    }
}
