<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('products')) { // Cek apakah tabel sudah ada sebelum membuat
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('sku');
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('supplier_id')->nullable();
                $table->decimal('purchase_price', 10, 2);
                $table->decimal('sale_price', 10, 2);
                $table->integer('stock');
                $table->integer('stock_minimum')->default(5);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }
    


    public function down()
    {
        Schema::dropIfExists('products');
    }
};
