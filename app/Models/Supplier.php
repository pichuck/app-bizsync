<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'contact_person',
        'contact_phone',
        'tax_id',
        'bank_name',
        'bank_account',
        'bank_account_name',
        'payment_term',
        'products_supplied',
        'notes',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user who created this supplier.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this supplier.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the transactions associated with this supplier.
     */
    public function transactions()
    {
        return $this->hasMany(Transaksi::class, 'contact_id')
                    ->where('type', 'purchase');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('products_supplied', 'like', "%{$search}%");
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get payment term text
     */
    public function getPaymentTermTextAttribute()
    {
        $terms = [
            'cod' => 'Cash on Delivery',
            'net7' => 'NET 7 Hari',
            'net14' => 'NET 14 Hari',
            'net30' => 'NET 30 Hari',
            'net60' => 'NET 60 Hari',
        ];

        return $terms[$this->payment_term] ?? $this->payment_term;
    }
}