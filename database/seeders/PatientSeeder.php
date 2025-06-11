<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan semua user dengan role 'pasien'
        $patientUsers = User::role('pasien')->get();

        // Buat pasien untuk setiap user dengan role 'pasien'
        foreach ($patientUsers as $user) {
            Patient::factory()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                // Faker akan mengisi sisa kolom
            ]);
        }

        // Buat beberapa pasien tanpa akun user (misal: pendaftaran offline atau anak-anak)
        Patient::factory(5)->create([
            'user_id' => null,
        ]);
    }
}