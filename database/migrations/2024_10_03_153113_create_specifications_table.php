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
        Schema::create('specifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('unit_serial')->unique();
            $table->string('ram')->nullable();
            $table->string('cpu')->nullable();
            $table->string('storage')->nullable();
            $table->string('display')->nullable();
            $table->string('os')->nullable();
            $table->string('vga')->nullable();
            $table->string('battery')->nullable();
            $table->timestamps();

            $table->foreign('unit_serial')->references('serial')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specifications');
    }
};
