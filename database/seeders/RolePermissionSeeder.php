<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // User Management
            'users-list',
            'users-create',
            'users-edit',
            'users-delete',
            
            // Role Management
            'roles-list',
            'roles-create',
            'roles-edit',
            'roles-delete',
            
            // Inventory Management
            'inventory-list',
            'inventory-create',
            'inventory-edit',
            'inventory-delete',
            'inventory-reports',
            
            // Finance Management
            'finance-list',
            'finance-create',
            'finance-edit',
            'finance-delete',
            'finance-reports',
            
            // Dashboard Access
            'dashboard-super-admin',
            'dashboard-admin',
            'dashboard-inventory',
            'dashboard-finance',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles
        $superAdmin = Role::create(['name' => 'super-admin']);
        $admin = Role::create(['name' => 'admin']);
        $inventoryAdmin = Role::create(['name' => 'admin-inventory']);
        $financeAdmin = Role::create(['name' => 'admin-finance']);

        // Assign Permissions to Roles
        
        // Super Admin - All Permissions
        $superAdmin->syncPermissions(Permission::all());

        // Admin - Basic Management
        $admin->syncPermissions([
            'users-list',
            'users-create',
            'users-edit',
            'dashboard-admin',
        ]);

        // Inventory Admin
        $inventoryAdmin->syncPermissions([
            'inventory-list',
            'inventory-create',
            'inventory-edit',
            'inventory-delete',
            'inventory-reports',
            'dashboard-inventory',
        ]);

        // Finance Admin
        $financeAdmin->syncPermissions([
            'finance-list',
            'finance-create',
            'finance-edit',
            'finance-delete',
            'finance-reports',
            'dashboard-finance',
        ]);
    }
}