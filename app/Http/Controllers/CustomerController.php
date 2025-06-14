<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        // Pastikan user memiliki role admin-finance
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Ambil data customers dari database dengan total receivables
        $customers = Customer::select('id', 'name', 'phone', 'email', 'credit_limit', 'status')
            ->withCount(['transactions as total_transactions' => function($query) {
                $query->where('type', 'sale');
            }])
            ->withSum(['transactions as total_receivables' => function($query) {
                $query->where('status', 'unpaid')
                      ->where('payment_method', 'credit')
                      ->where('type', 'sale');
            }], 'total')
            ->get();
            
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
        
        // Ambil data customer dengan transaksi terkait
        $customer = Customer::with(['transactions' => function($query) {
                $query->latest('date')->limit(5);
            }])
            ->withSum(['transactions as outstanding_balance' => function($query) {
                $query->where('status', 'unpaid')
                      ->where('payment_method', 'credit')
                      ->where('type', 'sale');
            }], 'total')
            ->findOrFail($id);
            
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