<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Import Role model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'), // Password default: password
                'email_verified_at' => now(),
                'role' => 'admin' // Set role langsung di sini
            ]
        );
        $admin->assignRole('admin'); // Assign role via Spatie Permission

        // Buat CS User
        $cs = User::firstOrCreate(
            ['email' => 'cs@example.com'],
            [
                'name' => 'Customer Service',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'cs'
            ]
        );
        $cs->assignRole('cs');

        // Buat Perawat User
        $perawat = User::firstOrCreate(
            ['email' => 'perawat@example.com'],
            [
                'name' => 'Perawat Medis',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'perawat'
            ]
        );
        $perawat->assignRole('perawat');

        // Buat 10 Pasien Dummy
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('pasien'); // Assign role pasien ke user dummy
            $user->role = 'pasien'; // Update kolom role di tabel users
            $user->save();
        });
    }
}