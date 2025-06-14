<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'description',
        'quantity',
        'price',
        'subtotal',
        'product_id'
    ];

    protected $casts = [
        'price' => 'float',
        'subtotal' => 'float',
        'quantity' => 'integer',
    ];

    /**
     * Relasi dengan transaksi
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    /**
     * Relasi dengan produk (jika ada)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Set subtotal otomatis saat mengubah quantity atau price
     */
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = $value;
        
        // Hitung subtotal
        if (isset($this->attributes['price'])) {
            $this->attributes['subtotal'] = $value * $this->attributes['price'];
        }
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value;
        
        // Hitung subtotal
        if (isset($this->attributes['quantity'])) {
            $this->attributes['subtotal'] = $value * $this->attributes['quantity'];
        }
    }
}