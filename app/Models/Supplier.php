<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'address',
        'phone',
        'email',
        'credit_limit',
        'total_payables',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'total_payables' => 'decimal:2',
    ];

    // Relationship with transactions or purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Relationship with payables
    public function payables()
    {
        return $this->hasMany(Payable::class);
    }

    // Method to calculate total payables
    public function calculateTotalPayables()
    {
        return $this->payables()->sum('amount');
    }

    // Method to get remaining credit limit
    public function getRemainingCreditLimit()
    {
        return $this->credit_limit - $this->total_payables;
    }
}