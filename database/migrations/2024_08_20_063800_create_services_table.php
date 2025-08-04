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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('service_category');
            $table->string('label');
            $table->string('cpu');
            $table->integer('ram');
            $table->integer('hdd')->nullable();
            $table->integer('ssd')->nullable();
            $table->string('os');
            $table->string('vga');
            $table->string('brand');
            $table->string('model');
            $table->string('display');
            $table->string('battery')->nullable();
            $table->string('price')->nullable();
            $table->string('description')->nullable();
            $table->string('device_category')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
