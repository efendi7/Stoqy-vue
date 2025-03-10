<?php

// File: database/migrations/xxxx_xx_xx_create_activities_table.php
// Replace xxxx_xx_xx with the current date in the format YYYY_MM_DD

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // create, read, update, delete
            $table->string('module'); // product, supplier, category, etc.
            $table->text('description')->nullable();
            $table->json('properties')->nullable(); // store additional data
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Add indexes for faster queries
            $table->index('action');
            $table->index('module');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};