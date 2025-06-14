<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Validasi role admin-finance
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        // Ambil data transaksi dari database
        $query = Transaksi::with('contact')
                    ->ofType($request->type)
                    ->search($request->search)
                    ->dateBetween($request->date_from, $request->date_to)
                    ->orderBy('date', 'desc');

        $transactions = $query->get();

        // Hitung summary
        $summary = [
            'total_transactions' => $transactions->count(),
            'total_income' => $transactions->whereIn('type', ['sale', 'income'])->sum('total'),
            'total_expense' => $transactions->whereIn('type', ['purchase', 'expense'])->sum('total'),
            'pending_payments' => $transactions->where('status', 'unpaid')->count()
        ];

        return Inertia::render('Dashboard/Finance/Transaksi', [
            'user' => $user,
            'transactions' => $transactions,
            'summary' => $summary
        ]);
    }

    public function create()
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        // Load customers for dropdown
        $customers = Customer::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);
        
        // Load suppliers for dropdown 
        $suppliers = [];
        try {
            if (class_exists('App\\Models\\Supplier')) {
                $suppliers = app('App\\Models\\Supplier')::where('status', 'active')
                    ->orderBy('name')
                    ->get(['id', 'name', 'email', 'phone']);
            }
        } catch (\Exception $e) {
            // Handle error silently or log it
            \Log::warning("Error loading suppliers: " . $e->getMessage());
        }
        
        // Load general contacts for income/expense
        $contacts = Contact::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);

        // Check if data was loaded successfully
        \Log::info("Customers loaded: " . $customers->count());
        \Log::info("Contacts loaded: " . $contacts->count());

        return Inertia::render('Dashboard/Finance/CRUDtransaksi/Create', [
            'user' => $user,
            'customers' => $customers,
            'suppliers' => $suppliers,
            'contacts' => $contacts,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validasi data request
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:sale,purchase,income,expense',
            'contact_id' => 'nullable|integer',
            'description' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit',
            'due_date' => 'nullable|date|after_or_equal:date',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Set status berdasarkan metode pembayaran
            $status = 'paid';
            if ($validated['payment_method'] === 'credit') {
                $status = 'unpaid';
            }

            // Handle upload bukti pembayaran jika ada
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Tentukan contact_type dan ambil nama kontak
            $contactType = null;
            $contactName = null;
            
            if ($validated['contact_id']) {
                if ($validated['type'] === 'sale') {
                    $contactType = Customer::class;
                    $customer = Customer::find($validated['contact_id']);
                    $contactName = $customer ? $customer->name : null;
                } elseif ($validated['type'] === 'purchase') {
                    $contactType = class_exists('App\\Models\\Supplier') ? 'App\\Models\\Supplier' : null;
                    try {
                        $supplier = app('App\\Models\\Supplier')::find($validated['contact_id']);
                        $contactName = $supplier ? $supplier->name : null;
                    } catch (\Exception $e) {
                        \Log::warning("Error finding supplier: " . $e->getMessage());
                    }
                } else {
                    $contactType = Contact::class;
                    $contact = Contact::find($validated['contact_id']);
                    $contactName = $contact ? $contact->name : null;
                }
            }

            // Buat transaksi baru
            $transaction = Transaksi::create([
                'date' => $validated['date'],
                'type' => $validated['type'],
                'contact_id' => $validated['contact_id'],
                'contact_type' => $contactType,
                'contact_name' => $contactName,
                'description' => $validated['description'],
                'total' => $validated['total'],
                'payment_method' => $validated['payment_method'],
                'due_date' => $validated['due_date'] ?? null,
                'status' => $status,
                'payment_proof' => $paymentProofPath,
                'notes' => $validated['notes'] ?? null,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Simpan item transaksi jika ada
            if (isset($validated['items']) && is_array($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    $transaction->items()->create([
                        'description' => $item['description'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                        'product_id' => $item['product_id'] ?? null,
                    ]);
                }
            }

            // Commit transaksi database
            DB::commit();

            return redirect()->route('finance.transactions')
                ->with('success', 'Transaksi berhasil ditambahkan');
                
        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        // Ambil transaksi dari database dengan eager loading
        $transaction = Transaksi::with(['contact', 'items', 'creator'])
                        ->findOrFail($id);

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

        // Ambil transaksi dari database
        $transaction = Transaksi::with(['items'])
                        ->findOrFail($id);

        // Load customers dan suppliers untuk dropdown
        $customers = Customer::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);
        
        $suppliers = Supplier::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);
        
        // Gunakan general contacts untuk income/expense
        $contacts = Contact::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);

        return Inertia::render('Dashboard/Finance/CRUDtransaksi/Update', [
            'user' => $user,
            'transaction' => $transaction,
            'customers' => $customers,
            'suppliers' => $suppliers,
            'contacts' => $contacts,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        // Validasi data
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:sale,purchase,income,expense',
            'contact_id' => 'nullable|integer',
            'description' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit',
            'due_date' => 'nullable|date|after_or_equal:date',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|integer|exists:transaksi_items,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Cari transaksi yang akan diupdate
            $transaction = Transaksi::findOrFail($id);

            // Set status berdasarkan metode pembayaran
            $status = $transaction->status;
            if ($validated['payment_method'] === 'cash') {
                $status = 'paid';
            } elseif ($validated['payment_method'] === 'credit' && $transaction->payment_method === 'cash') {
                $status = 'unpaid';
            }

            // Handle upload bukti pembayaran jika ada
            if ($request->hasFile('payment_proof')) {
                // Hapus file lama jika ada
                if ($transaction->payment_proof) {
                    Storage::disk('public')->delete($transaction->payment_proof);
                }
                
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
                $validated['payment_proof'] = $paymentProofPath;
            }

            // Update transaksi
            $transaction->update([
                'date' => $validated['date'],
                'type' => $validated['type'],
                'contact_id' => $validated['contact_id'],
                'description' => $validated['description'],
                'total' => $validated['total'],
                'payment_method' => $validated['payment_method'],
                'due_date' => $validated['due_date'] ?? null,
                'status' => $status,
                'payment_proof' => $validated['payment_proof'] ?? $transaction->payment_proof,
                'notes' => $validated['notes'] ?? $transaction->notes,
                'updated_by' => $user->id,
            ]);

            // Update item transaksi jika ada
            if (isset($validated['items']) && is_array($validated['items'])) {
                // Simpan ID item yang ada di request
                $existingItemIds = collect($validated['items'])
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Hapus item yang tidak ada di request
                $transaction->items()
                    ->whereNotIn('id', $existingItemIds)
                    ->delete();

                // Update atau buat item baru
                foreach ($validated['items'] as $itemData) {
                    if (isset($itemData['id'])) {
                        // Update item yang sudah ada
                        $item = TransaksiItem::find($itemData['id']);
                        if ($item) {
                            $item->update([
                                'description' => $itemData['description'],
                                'quantity' => $itemData['quantity'],
                                'price' => $itemData['price'],
                                'subtotal' => $itemData['quantity'] * $itemData['price'],
                                'product_id' => $itemData['product_id'] ?? null,
                            ]);
                        }
                    } else {
                        // Buat item baru
                        $transaction->items()->create([
                            'description' => $itemData['description'],
                            'quantity' => $itemData['quantity'],
                            'price' => $itemData['price'],
                            'subtotal' => $itemData['quantity'] * $itemData['price'],
                            'product_id' => $itemData['product_id'] ?? null,
                        ]);
                    }
                }
            }

            // Commit transaksi database
            DB::commit();

            return redirect()->route('finance.transactions')
                ->with('success', 'Transaksi berhasil diperbarui');
                
        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        try {
            $transaction = Transaksi::findOrFail($id);
            
            // Hapus bukti pembayaran jika ada
            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }

            // Hapus transaksi (items akan terhapus otomatis karena cascade)
            $transaction->delete();

            return redirect()->route('finance.transactions')
                ->with('success', 'Transaksi berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()->route('finance.transactions')
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * API untuk mencari kontak (customer/supplier)
     * Modifikasi untuk menggunakan model sesuai tipe transaksi
     */
    public function searchContacts(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];
        
        // Cari berdasarkan tipe transaksi
        if ($type === 'sale') {
            // Cari di model Customer
            $customers = Customer::where('status', 'active')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'name', 'email', 'phone as contact', 'credit_limit']);
                
            $results = $customers->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'contact' => $customer->contact,
                    'credit_limit' => $customer->credit_limit,
                    'model' => 'customer'
                ];
            });
        } 
        elseif ($type === 'purchase') {
            // Cari di model Supplier
            $suppliers = Supplier::where('status', 'active')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'name', 'email', 'phone as contact']);
                
            $results = $suppliers->map(function($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'email' => $supplier->email,
                    'contact' => $supplier->contact,
                    'model' => 'supplier'
                ];
            });
        }
        else {
            // Untuk income/expense, cari di Contact
            $contacts = Contact::where('status', 'active')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'name', 'email', 'phone as contact']);
                
            $results = $contacts->map(function($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'contact' => $contact->contact,
                    'model' => 'contact'
                ];
            });
        }

        // Jika tidak ada hasil, tambahkan opsi untuk membuat baru
        if (count($results) == 0) {
            $results[] = [
                'id' => 0,
                'name' => $query,
                'email' => '',
                'contact' => 'Gunakan nama ini',
                'is_new' => true
            ];
        }

        return response()->json($results);
    }

    /**
     * Mendapatkan semua customer untuk dropdown select
     */
    public function getCustomers()
    {
        $customers = Customer::where('status', 'active')
            ->get(['id', 'name', 'email', 'phone', 'credit_limit']);
            
        return response()->json($customers);
    }

    /**
     * Mendapatkan semua supplier untuk dropdown select
     */
    public function getSuppliers()
    {
        $suppliers = Supplier::where('status', 'active')
            ->get(['id', 'name', 'email', 'phone']);
            
        return response()->json($suppliers);
    }
    
    /**
     * Mengubah status pembayaran transaksi
     */
    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user()->load('roles');
        if (!$user->roles->contains('name', 'admin-finance')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'status' => 'required|in:paid,unpaid,partial'
        ]);

        try {
            $transaction = Transaksi::findOrFail($id);
            
            // Jika metode pembayaran cash, status selalu paid
            if ($transaction->payment_method === 'cash') {
                return redirect()->back()
                    ->with('info', 'Transaksi dengan metode pembayaran Cash selalu berstatus Lunas.');
            }
            
            $transaction->update([
                'status' => $request->status,
                'updated_by' => $user->id
            ]);

            return redirect()->back()
                ->with('success', 'Status transaksi berhasil diperbarui');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}