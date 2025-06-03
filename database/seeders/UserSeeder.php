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
        // Hapus user yang sudah ada jika perlu
        User::where('email', 'like', '%@bizsync.com')->delete();
        
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
        echo "Super Admin created with role: " . $superAdmin->roles->pluck('name')->join(', ') . "\n";

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
        echo "Admin created with role: " . $admin->roles->pluck('name')->join(', ') . "\n";

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
        echo "Inventory Admin created with role: " . $inventoryAdmin->roles->pluck('name')->join(', ') . "\n";

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
        echo "Finance Admin created with role: " . $financeAdmin->roles->pluck('name')->join(', ') . "\n";
        
        // Debug: Tampilkan semua roles yang ada
        echo "\nAll available roles:\n";
        foreach (Role::all() as $role) {
            echo "- {$role->name}\n";
        }
    }
}