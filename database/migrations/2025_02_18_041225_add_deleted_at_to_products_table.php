<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    if (!Schema::hasColumn('products', 'deleted_at')) { // Cek apakah kolom sudah ada
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });
    }
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();  // Menghapus kolom 'deleted_at'
        });
    }
}
