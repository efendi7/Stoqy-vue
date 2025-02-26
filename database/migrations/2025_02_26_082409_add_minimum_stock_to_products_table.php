<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Mengecek apakah kolom minimum_stock sudah ada
        if (!Schema::hasColumn('products', 'minimum_stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('minimum_stock')->default(0);
            });
        }
    }
    
    public function down()
    {
        // Hapus kolom minimum_stock jika migrasi dibatalkan
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('minimum_stock');
        });
    }
    
 
};
