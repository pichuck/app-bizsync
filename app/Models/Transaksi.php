<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 
        'type', 
        'contact_id',
        'contact_type', // Tambahkan ini untuk polymorphic
        'contact_name', 
        'description', 
        'total', 
        'payment_method', 
        'due_date',
        'status',
        'payment_proof',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'total' => 'float',
    ];

    /**
     * Relasi polymorphic dengan model Contact/Customer/Supplier
     */
    public function contactable()
    {
        if ($this->contact_type) {
            return $this->morphTo('contactable', 'contact_type', 'contact_id');
        } else {
            // Fallback ke metode tradisional jika contact_type kosong
            if ($this->type === 'sale') {
                return $this->belongsTo(Customer::class, 'contact_id');
            } elseif ($this->type === 'purchase') {
                return $this->belongsTo(Supplier::class, 'contact_id');
            } else {
                return $this->belongsTo(Contact::class, 'contact_id');
            }
        }
    }

    /**
     * Relasi langsung ke Customer (untuk transaksi penjualan)
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'contact_id')
            ->where('contact_type', Customer::class);
    }

    /**
     * Relasi langsung ke Supplier (untuk transaksi pembelian)
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'contact_id')
            ->where('contact_type', Supplier::class);
    }

    /**
     * Relasi langsung ke Contact (untuk transaksi umum)
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id')
            ->where('contact_type', Contact::class);
    }

    /**
     * Relasi dengan model User (created by)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi dengan model User (updated by)
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi dengan item transaksi
     */
    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    /**
     * Mendapatkan label type yang lebih user friendly
     */
    public function getTypeTextAttribute()
    {
        $types = [
            'sale' => 'Penjualan',
            'purchase' => 'Pembelian',
            'income' => 'Pendapatan',
            'expense' => 'Pengeluaran',
        ];
        
        return $types[$this->type] ?? $this->type;
    }

    /**
     * Mendapatkan label status yang lebih user friendly
     */
    public function getStatusTextAttribute()
    {
        if ($this->payment_method === 'cash') {
            return 'Lunas (Cash)';
        }
        
        $statuses = [
            'paid' => 'Lunas',
            'unpaid' => 'Belum Lunas',
            'partial' => 'Sebagian',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Cek apakah transaksi sudah melewati jatuh tempo
     */
    public function getIsPastDueAttribute()
    {
        if ($this->status === 'paid' || !$this->due_date) {
            return false;
        }
        
        return $this->due_date->isPast();
    }

    /**
     * Scope untuk filter berdasarkan jenis transaksi
     */
    public function scopeOfType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * Scope untuk pencarian berdasarkan deskripsi atau contact
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('description', 'like', "%{$search}%")
                        ->orWhereHas('contact', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeDateBetween($query, $start, $end)
    {
        if ($start) {
            $query->where('date', '>=', $start);
        }
        
        if ($end) {
            $query->where('date', '<=', $end);
        }
        
        return $query;
    }
}