<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function superAdmin()
    {
        // Cek role di dalam controller
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/SuperAdmin/Index', [
            'user' => auth()->user(),
            'stats' => [
                'total_users' => \App\Models\User::count(),
                'total_roles' => \App\Models\Role::count(),
                'active_users' => \App\Models\User::where('status', 'active')->count(),
            ]
        ]);
    }

    public function admin()
    {
        // Cek role di dalam controller - sesuaikan dengan yang di AuthController
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/Admin/Index', [
            'user' => auth()->user(),
        ]);
    }

    public function inventory()
    {
        // Cek role di dalam controller - sesuaikan dengan yang di AuthController
        if (!auth()->user()->hasRole('admin-inventory')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/Inventory/Index', [
            'user' => auth()->user(),
        ]);
    }

    public function finance()
    {
        // Cek role di dalam controller - sesuaikan dengan yang di AuthController
        if (!auth()->user()->hasRole('admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/Finance/Index', [
            'user' => auth()->user(),
        ]);
    }
}