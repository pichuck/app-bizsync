<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        // Validasi role admin-finance
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        // Sample data transaksi yang sama dengan DashboardController
        $transactions = [
            [
                'id' => 1,
                'date' => '2024-06-10',
                'description' => 'Penjualan Produk A',
                'contact' => ['name' => 'PT. Sukses Makmur'],
                'total' => 5000000,
                'payment_method' => 'cash',
                'status' => 'paid',
                'type' => 'sale'
            ],
            [
                'id' => 2,
                'date' => '2024-06-09',
                'description' => 'Pembelian Bahan Baku',
                'contact' => ['name' => 'CV. Bahan Jaya'],
                'total' => 2000000,
                'payment_method' => 'transfer',
                'status' => 'unpaid',
                'type' => 'purchase'
            ],
            [
                'id' => 3,
                'date' => '2024-06-08',
                'description' => 'Pendapatan Bunga Bank',
                'contact' => ['name' => 'Bank Mandiri'],
                'total' => 150000,
                'payment_method' => 'transfer',
                'status' => 'paid',
                'type' => 'income'
            ],
            [
                'id' => 4,
                'date' => '2024-06-07',
                'description' => 'Bayar Listrik Kantor',
                'contact' => ['name' => 'PLN'],
                'total' => 800000,
                'payment_method' => 'transfer',
                'status' => 'paid',
                'type' => 'expense'
            ]
        ];

        return Inertia::render('Dashboard/Finance/Transaksi', [
            'user' => $user,
            'transactions' => $transactions,
            'summary' => [
                'total_transactions' => count($transactions),
                'total_income' => array_sum(array_column(array_filter($transactions, fn($t) => in_array($t['type'], ['sale', 'income'])), 'total')),
                'total_expense' => array_sum(array_column(array_filter($transactions, fn($t) => in_array($t['type'], ['purchase', 'expense'])), 'total')),
                'pending_payments' => count(array_filter($transactions, fn($t) => $t['status'] === 'unpaid'))
            ]
        ]);
    }

    public function create()
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        // FIXED: Updated to match the actual Vue file path
        return Inertia::render('Dashboard/Finance/CRUDtransaksi/Create', [
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi dan simpan data
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:sale,purchase,income,expense',
            'contact_id' => 'nullable|integer',
            'description' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit',
            'due_date' => 'nullable|date|after_or_equal:date',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Logic untuk menyimpan transaction ke database
        // Transaction::create($request->all());

        return redirect()->route('finance.transactions')
            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function show($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        // Dummy data untuk detail transaction
        $transaction = [
            'id' => $id,
            'date' => '2024-06-10',
            'type' => 'sale', // Tambahkan tipe transaksi
            'description' => 'Penjualan Produk A',
            'contact' => ['name' => 'PT. Sukses Makmur', 'address' => 'Jl. Merdeka No. 123, Jakarta'],
            'contact_id' => 5,
            'total' => 5000000,
            'payment_method' => 'cash',
            'status' => 'paid',
            'created_at' => '2024-06-10 08:30:00',
            'updated_at' => '2024-06-10 08:30:00',
            // Tambahkan data dummy items jika dibutuhkan
            'items' => [
                [
                    'description' => 'Produk A',
                    'quantity' => 2,
                    'price' => 2500000,
                ]
            ],
        ];

        // PENTING: Ubah path render ke Read.vue
        return Inertia::render('Dashboard/Finance/CRUDtransaksi/Read', [
            'user' => $user,
            'transaction' => $transaction,
        ]);
    }

    public function edit($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        // Dummy data untuk edit transaction (atau ambil dari database)
        $transaction = [
            'id' => $id,
            'date' => '2024-06-10',
            'type' => 'sale',
            'description' => 'Penjualan Produk A',
            'contact' => ['name' => 'PT. Sukses Makmur', 'id' => 5],
            'contact_id' => 5,
            'total' => 5000000,
            'payment_method' => 'cash',
            'status' => 'paid',
            'due_date' => null,
        ];

        // PENTING: Pastikan path ini sesuai dengan lokasi file Update.vue
        return Inertia::render('Dashboard/Finance/CRUDtransaksi/Update', [
            'user' => $user,
            'transaction' => $transaction,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:sale,purchase,income,expense',
            'contact_id' => 'nullable|integer',
            'description' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit',
            'due_date' => 'nullable|date|after_or_equal:date',
        ]);

        // Logic untuk update transaction
        // Transaction::find($id)->update($request->all());

        return redirect()->route('finance.transactions')
            ->with('success', 'Transaksi berhasil diupdate');
    }

    public function destroy($id)
    {
        // Logic untuk hapus transaction
        // Transaction::find($id)->delete();

        return redirect()->route('finance.transactions')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}