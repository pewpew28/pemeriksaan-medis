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
            // Buat 1-3 pemeriksaan untuk setiap pasien
            Examination::factory(rand(1, 3))->create([
                'patient_id' => $patient->id,
                'status' => 'pending', // Default status
                'payment_status' => 'pending', // Default payment status
            ]);
        }

        // Ambil beberapa pemeriksaan dan ubah statusnya menjadi 'completed'
        // untuk tujuan testing fitur unduh hasil
        Examination::inRandomOrder()->limit(5)->get()->each(function ($examination) {
            $examination->update([
                'status' => 'completed',
                'result_available' => true,
                'payment_status' => 'paid',
            ]);
            // Jika ingin menambahkan media dummy (file PDF), bisa di sini
            // $examination->addMedia(database_path('seed_files/dummy_result.pdf')) // Pastikan file ini ada
            //             ->preservingOriginal()
            //             ->toMediaCollection('results');
        });
    }
}