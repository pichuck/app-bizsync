<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index()
    {
        // Data dummy untuk tampilan
        $suppliers = [
            [
                'id' => 1,
                'name' => 'PT. Maju Jaya',
                'contact' => '08123456789',
                'email' => 'contact@majujaya.com',
                'address' => 'Jl. Raya Industri No. 123, Jakarta',
                'credit_limit' => 50000000,
                'total_payables' => 15000000,
                'created_at' => '2024-01-15',
                'updated_at' => '2024-01-15'
            ],
            [
                'id' => 2,
                'name' => 'CV. Berkah Sejahtera',
                'contact' => '08198765432',
                'email' => 'info@berkahsejahtera.com',
                'address' => 'Jl. Perdagangan No. 456, Surabaya',
                'credit_limit' => 75000000,
                'total_payables' => 25000000,
                'created_at' => '2024-01-10',
                'updated_at' => '2024-01-20'
            ],
            [
                'id' => 3,
                'name' => 'PT. Global Supplier',
                'contact' => '08187654321',
                'email' => 'sales@globalsupplier.com',
                'address' => 'Jl. Industri Raya No. 789, Bandung',
                'credit_limit' => 100000000,
                'total_payables' => 40000000,
                'created_at' => '2024-01-05',
                'updated_at' => '2024-01-25'
            ],
            [
                'id' => 4,
                'name' => 'Toko Elektronik Jaya',
                'contact' => '08176543210',
                'email' => 'elektronik@jaya.com',
                'address' => 'Jl. Elektronik No. 321, Medan',
                'credit_limit' => 30000000,
                'total_payables' => 8000000,
                'created_at' => '2024-01-12',
                'updated_at' => '2024-01-18'
            ],
            [
                'id' => 5,
                'name' => 'PT. Teknologi Maju',
                'contact' => '08165432109',
                'email' => 'tech@majutech.com',
                'address' => 'Jl. Teknologi No. 654, Malang',
                'credit_limit' => 85000000,
                'total_payables' => 35000000,
                'created_at' => '2024-01-08',
                'updated_at' => '2024-01-22'
            ]
        ];

        return Inertia::render('Dashboard/Finance/Supliers', [
            'suppliers' => $suppliers,
            'user' => auth()->user()
        ]);
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return Inertia::render('Dashboard/Finance/Suppliers/Create', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);

        // Untuk saat ini hanya redirect dengan pesan sukses
        // Nanti bisa ditambahkan logic untuk menyimpan ke database
        
        return redirect()->route('finance.suppliers')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    /**
     * Display the specified supplier.
     */
    public function show($id)
    {
        // Data dummy untuk detail supplier
        $supplier = [
            'id' => $id,
            'name' => 'PT. Maju Jaya',
            'contact' => '08123456789',
            'email' => 'contact@majujaya.com',
            'address' => 'Jl. Raya Industri No. 123, Jakarta',
            'credit_limit' => 50000000,
            'total_payables' => 15000000,
            'created_at' => '2024-01-15',
            'updated_at' => '2024-01-15'
        ];

        return Inertia::render('Dashboard/Finance/Suppliers/Show', [
            'supplier' => $supplier,
            'user' => auth()->user()
        ]);
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit($id)
    {
        // Data dummy untuk edit supplier
        $supplier = [
            'id' => $id,
            'name' => 'PT. Maju Jaya',
            'contact' => '08123456789',
            'email' => 'contact@majujaya.com',
            'address' => 'Jl. Raya Industri No. 123, Jakarta',
            'credit_limit' => 50000000,
            'total_payables' => 15000000,
            'created_at' => '2024-01-15',
            'updated_at' => '2024-01-15'
        ];

        return Inertia::render('Dashboard/Finance/Suppliers/Edit', [
            'supplier' => $supplier,
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);

        // Untuk saat ini hanya redirect dengan pesan sukses
        // Nanti bisa ditambahkan logic untuk update ke database
        
        return redirect()->route('finance.suppliers')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy($id)
    {
        // Untuk saat ini hanya redirect dengan pesan sukses
        // Nanti bisa ditambahkan logic untuk hapus dari database
        
        return redirect()->route('finance.suppliers')
            ->with('success', 'Supplier berhasil dihapus');
    }
}