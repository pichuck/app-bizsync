<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@bizsync.com',
            'username' => 'superadmin',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'status' => 'active',
        ]);
        $superAdmin->addRole('super-admin');

        // Create Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@bizsync.com',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'phone' => '081234567891',
            'status' => 'active',
        ]);
        $admin->addRole('admin');

        // Create Inventory Admin
        $inventoryAdmin = User::create([
            'name' => 'Inventory Administrator',
            'email' => 'inventory@bizsync.com',
            'username' => 'inventory_admin',
            'password' => Hash::make('password123'),
            'phone' => '081234567892',
            'status' => 'active',
        ]);
        $inventoryAdmin->addRole('admin-inventory');

        // Create Finance Admin
        $financeAdmin = User::create([
            'name' => 'Finance Administrator',
            'email' => 'finance@bizsync.com',
            'username' => 'finance_admin',
            'password' => Hash::make('password123'),
            'phone' => '081234567893',
            'status' => 'active',
        ]);
        $financeAdmin->addRole('admin-finance');
    }
}