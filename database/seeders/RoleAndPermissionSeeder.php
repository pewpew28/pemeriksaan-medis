<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Import Role model

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'cs']);
        Role::firstOrCreate(['name' => 'perawat']);
        Role::firstOrCreate(['name' => 'pasien']);

        // Anda bisa menambahkan permissions di sini jika diperlukan.
        // Contoh:
        // Permission::firstOrCreate(['name' => 'manage patients']);
        // $adminRole = Role::findByName('admin');
        // $adminRole->givePermissionTo('manage patients');
    }
}