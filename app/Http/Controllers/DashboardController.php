<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{


     use AuthorizesRequests;
     
    public function superAdmin()
    {
        $this->authorize('dashboard-super-admin');
        
        return Inertia::render('Dashboard/SuperAdmin', [
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
        $this->authorize('dashboard-admin');
        
        return Inertia::render('Dashboard/Admin', [
            'user' => auth()->user(),
        ]);
    }

    public function inventory()
    {
        $this->authorize('dashboard-inventory');
        
        return Inertia::render('Dashboard/Inventory/Index', [
            'user' => auth()->user(),
        ]);
    }

    public function finance()
    {
        $this->authorize('dashboard-finance');
        
        return Inertia::render('Dashboard/Finance/Index', [
            'user' => auth()->user(),
        ]);
    }
}