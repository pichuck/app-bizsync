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
        // Validasi role admin-finance
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        return Inertia::render('Dashboard/Finance/CRUDsupliers/Create', [
            'user' => $user
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
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        // Data dummy untuk detail supplier
        $supplier = [
            'id' => $id,
            'name' => 'PT. Supplier Jaya',
            'email' => 'contact@supplierjaya.com',
            'phone' => '021-1234567',
            'address' => 'Jl. Industri No. 123, Jakarta',
            'type' => 'manufacturer',
            'pic_name' => 'Budi Santoso',
            'pic_position' => 'Sales Manager',
            'pic_phone' => '081234567890',
            'tax_id' => '12.345.678.9-012.000',
            'bank_account' => 'BCA - 1234567890 (a.n PT Supplier Jaya)',
            'notes' => 'Supplier utama untuk bahan baku produksi',
            'logo_url' => 'https://via.placeholder.com/150',
            'status' => 'active',
            'created_at' => '2023-01-15',
            'updated_at' => '2024-05-20',
            // Data transaksi dummy
            'transactions' => [
                [
                    'id' => 201,
                    'date' => '2024-05-15',
                    'description' => 'Pembelian Bahan Baku',
                    'reference_no' => 'PO-2024-001',
                    'total' => 12500000,
                    'status' => 'paid',
                    'payment_method' => 'transfer'
                ],
                [
                    'id' => 202,
                    'date' => '2024-06-01',
                    'description' => 'Pembelian Spare Part Mesin',
                    'reference_no' => 'PO-2024-002',
                    'total' => 8750000,
                    'status' => 'unpaid',
                    'payment_method' => 'credit'
                ]
            ],
            // Data kategori produk dummy
            'product_categories' => ['Bahan Baku', 'Spare Part', 'Packaging']
        ];

        // Ubah path render ke yang benar
        return Inertia::render('Dashboard/Finance/CRUDsupliers/Read', [
            'supplier' => $supplier,
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }
        
        // Dummy data untuk contoh - seharusnya diambil dari database
        $supplier = [
            'id' => $id,
            'name' => 'PT Supplier Jaya',
            'email' => 'contact@supplierjaya.com',
            'phone' => '021-1234567',
            'address' => 'Jl. Industri No. 123, Jakarta',
            'type' => 'manufacturer',
            'pic_name' => 'Budi Santoso',
            'pic_position' => 'Sales Manager',
            'pic_phone' => '081234567890',
            'tax_id' => '12.345.678.9-012.000',
            'bank_account' => 'BCA - 1234567890 (a.n PT Supplier Jaya)',
            'notes' => 'Supplier utama untuk bahan baku produksi',
            'logo_url' => 'https://via.placeholder.com/150',
            'status' => 'active',
        ];
        
        return Inertia::render('Dashboard/Finance/CRUDsupliers/Update', [
            'user' => $user,
            'supplier' => $supplier,
        ]);
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'type' => 'required|string|in:manufacturer,distributor,retailer,service',
            'pic_name' => 'nullable|string|max:255',
            'pic_position' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'bank_account' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'status' => 'required|string|in:active,inactive',
        ]);
        
        // Logic untuk update supplier
        
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