<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // permission utama
// Buat permission utama
        $manageOperational = Permission::firstOrCreate(['name' => 'manage-operational']);
        // $manageContent = Permission::firstOrCreate(['name' => 'manage-content']);

        // Buat role admin_operational dan tambahkan permission
        $adminOperational = Role::firstOrCreate(['name' => 'admin_operational']);
        $adminOperational->syncPermissions([$manageOperational]);

          // Buat role user tanpa permission
        Role::firstOrCreate(['name' => 'user']);

          // Buat atau update user admin_operational
        $adminOp = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Operational',
                'password' => Hash::make('admin123'),
            ]
        );
        $adminOp->assignRole($adminOperational);
    }
}
