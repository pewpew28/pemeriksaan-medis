<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder lain dalam urutan yang benar
        $this->call([
            RoleAndPermissionSeeder::class, // Ini penting dipanggil pertama untuk role
            UserSeeder::class,
            PatientSeeder::class,
            ServiceDataSeeder::class,
            // ExaminationSeeder::class,
        ]);
    }
}