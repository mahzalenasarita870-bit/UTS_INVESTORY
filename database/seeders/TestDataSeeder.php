<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori
        $kat1 = Category::create(['name' => 'Skincare']);
        $kat2 = Category::create(['name' => 'Bodycare']);

        // 2. Buat Produk
        Product::create([
            'category_id' => $kat1->id,
            'name' => 'Glow Serum 30ml',
            'sku' => 'GLOW-001',
            'stock' => 50,
            'purchase_price' => 45000,
            'selling_price' => 75000,
        ]);

        Product::create([
            'category_id' => $kat2->id,
            'name' => 'Body Lotion Brightening',
            'sku' => 'BODY-001',
            'stock' => 30,
            'purchase_price' => 35000,
            'selling_price' => 60000,
        ]);
    }
}