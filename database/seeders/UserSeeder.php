<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role ada
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // ========== OWNER ==========
        $owner1 = User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name'     => 'Super Admin 1',
                'password' => Hash::make('SecretDev123!'),
            ]
        );
        $owner1->assignRole($ownerRole);

        $owner2 = User::updateOrCreate(
            ['email' => 'superadmin2@gmail.com'],
            [
                'name'     => 'Super Admin 2',
                'password' => Hash::make('SecretDev123!'),
            ]
        );
        $owner2->assignRole($ownerRole);

        // ========== ADMIN ==========
        $admin1 = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin Staff 1',
                'password' => Hash::make('SecretDev123!'),
            ]
        );
        $admin1->assignRole($adminRole);

        $admin2 = User::updateOrCreate(
            ['email' => 'admin2@gmail.com'],
            [
                'name'     => 'Admin Staff 2',
                'password' => Hash::make('SecretDev123!'),
            ]
        );
        $admin2->assignRole($adminRole);
    }
}
