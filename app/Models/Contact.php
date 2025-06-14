<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'address',
        'pic_name',
        'pic_phone',
        'tax_id',
        'bank_account',
        'notes',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user who created this contact.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this contact.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the transactions associated with this contact.
     */
    public function transactions()
    {
        return $this->hasMany(Transaksi::class, 'contact_id');
    }

    /**
     * Scope untuk pencarian berdasarkan nama atau kontak
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
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
}