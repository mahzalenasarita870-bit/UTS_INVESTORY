<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Tabel Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        // 4. Tabel Purchases (Barang Masuk)
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->integer('quantity');
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->timestamps();
        });

// 5. Tabel Sales (Header Transaksi POS)
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->string('no_struk')->unique();
        $table->decimal('total', 12, 2);
        $table->decimal('bayar', 12, 2);
        $table->decimal('kembalian', 12, 2);
        $table->timestamps();
    });

    // 6. Tabel Sale Items (Detail Produk per Transaksi)
    Schema::create('sale_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
        $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
        $table->integer('quantity');
        $table->decimal('unit_price', 12, 2);
        $table->decimal('subtotal', 12, 2);
        $table->timestamps();
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('sale_items');
    Schema::dropIfExists('sales');
    Schema::dropIfExists('purchases');
    Schema::dropIfExists('products');
    Schema::dropIfExists('suppliers');
    Schema::dropIfExists('categories');
}
};