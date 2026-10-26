<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();
                $table->string('no_struk')->unique();
                $table->decimal('total', 12, 2);
                $table->decimal('bayar', 12, 2);
                $table->decimal('kembalian', 12, 2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sale_items')) {
            Schema::create('sale_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->integer('quantity'); // Disesuaikan dengan SaleItem.php
                $table->decimal('unit_price', 12, 2); // Disesuaikan dengan SaleItem.php
                $table->decimal('subtotal', 12, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};