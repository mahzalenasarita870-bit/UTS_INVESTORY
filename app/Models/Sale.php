<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

protected $fillable = [
    'no_struk',
    'total',
    'bayar',
    'kembalian',
    'payment_method',
    'user_id',
];
    

    // Relasi ke tabel detail (SaleItem)
    public function items()
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }
}