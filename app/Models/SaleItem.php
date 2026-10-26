<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi balik ke Sale Header
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    // Relasi ke Produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}