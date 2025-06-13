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
        
        // Ubah path render sesuai dengan lokasi file Vue
        return Inertia::render('Dashboard/Finance/CRUDcustomer/Create', [
            'user' => $user
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
        
        // Data dummy customer untuk contoh
        $customer = [
            'id' => $id,
            'name' => 'PT. ABC Corporation',
            'contact' => '0812-3456-7890',
            'email' => 'contact@abc-corp.com',
            'address' => 'Jl. Sudirman No. 123, Jakarta',
            'credit_limit' => 50000000,
            'outstanding_balance' => 15000000,
            'type' => 'company',
            'status' => 'active',
            'created_at' => '2023-01-15',
            'updated_at' => '2024-05-20',
            // Data transaksi dummy
            'transactions' => [
                [
                    'id' => 101,
                    'date' => '2024-05-15',
                    'type' => 'sale',
                    'description' => 'Penjualan Produk A',
                    'total' => 5000000,
                    'status' => 'paid',
                    'payment_method' => 'credit'
                ],
                [
                    'id' => 102,
                    'date' => '2024-06-01',
                    'type' => 'sale',
                    'description' => 'Penjualan Produk B',
                    'total' => 7500000,
                    'status' => 'unpaid',
                    'payment_method' => 'credit'
                ]
            ]
        ];
        
        // Ubah path render ke Read.vue yang benar
        return Inertia::render('Dashboard/Finance/CRUDcustomer/Read', [
            'user' => $user,
            'customer' => $customer,
        ]);
    }

    public function edit($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Data dummy customer untuk contoh
        $customer = [
            'id' => $id,
            'name' => 'PT. ABC Corporation',
            'contact' => '0812-3456-7890',
            'email' => 'contact@abc-corp.com',
            'address' => 'Jl. Sudirman No. 123, Jakarta',
            'credit_limit' => 50000000,
            'type' => 'company',
            'status' => 'active'
        ];
        
        // Perbaiki path render ke Update.vue
        return Inertia::render('Dashboard/Finance/CRUDcustomer/Update', [
            'user' => $user,
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Validasi form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
            'type' => 'required|string|in:individual,company',
            'status' => 'required|string|in:active,inactive',
            'profile_image' => 'nullable|image|max:2048',
        ]);
        
        // Di sini seharusnya ada logika update ke database
        // Misalnya: Customer::find($id)->update($validated);
        
        // Jika ada upload gambar, bisa disimpan dengan:
        if ($request->hasFile('profile_image')) {
            // Logic untuk upload dan simpan image
            // $path = $request->file('profile_image')->store('customers');
            // $customer->update(['profile_image' => $path]);
        }
        
        return redirect()->route('finance.customers')
            ->with('success', 'Pelanggan berhasil diperbarui');
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