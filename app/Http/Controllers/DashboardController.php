<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function superAdmin()
    {
        // Gunakan relationship check yang sama dengan AuthController
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'super-admin')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/SuperAdmin/Index', [
            'user' => $user,
            'stats' => [
                'total_users' => \App\Models\User::count(),
                'total_roles' => \App\Models\Role::count(),
                'active_users' => \App\Models\User::where('status', 'active')->count(),
            ]
        ]);
    }

    public function admin()
    {
        // Gunakan relationship check yang sama dengan AuthController
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/Admin/Index', [
            'user' => $user,
        ]);
    }

    public function inventory()
    {
        // Gunakan relationship check yang sama dengan AuthController
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-inventory')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/Inventory/Index', [
            'user' => $user,
        ]);
    }

    public function finance()
    {
        // Gunakan relationship check yang sama dengan AuthController
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/Finance/Index', [
            'user' => $user,
        ]);
    }
}