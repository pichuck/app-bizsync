<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        // Pastikan user memiliki role admin-finance
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Data dummy customers - ganti dengan data dari database
        $customers = [
            [
                'id' => 1,
                'name' => 'PT. ABC Corporation',
                'contact' => '0812-3456-7890',
                'credit_limit' => 50000000,
                'total_receivables' => 15000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'CV. XYZ Trading',
                'contact' => '0821-9876-5432',
                'credit_limit' => 25000000,
                'total_receivables' => 22000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Toko Makmur Jaya',
                'contact' => '0856-1111-2222',
                'credit_limit' => 10000000,
                'total_receivables' => 3000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        return Inertia::render('Dashboard/Finance/Customers', [
            'user' => $user,
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        return Inertia::render('Dashboard/Finance/CustomerForm', [
            'user' => $user,
            'customer' => null, // null untuk create mode
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Implement store logic here
        // Untuk sementara redirect kembali ke index
        return redirect()->route('finance.customers')->with('success', 'Customer berhasil ditambahkan');
    }

    public function show($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Implement show logic here
        return Inertia::render('Dashboard/Finance/CustomerDetail', [
            'user' => $user,
            'customer' => ['id' => $id], // dummy data
        ]);
    }

    public function edit($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Implement edit logic here
        return Inertia::render('Dashboard/Finance/CustomerForm', [
            'user' => $user,
            'customer' => ['id' => $id], // dummy data untuk edit mode
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Implement update logic here
        return redirect()->route('finance.customers')->with('success', 'Customer berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Implement delete logic here
        return redirect()->route('finance.customers')->with('success', 'Customer berhasil dihapus');
    }
}