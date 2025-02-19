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
        if (!Schema::hasTable('suppliers')) { // Cek apakah tabel sudah ada sebelum membuat
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('contact')->nullable(); // Menambahkan kolom contact
                $table->string('address')->nullable(); // Menambahkan kolom address
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
