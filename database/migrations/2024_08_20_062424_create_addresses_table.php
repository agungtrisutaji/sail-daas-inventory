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
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company_id')->nullable();
            $table->string('operational_unit_id')->nullable();
            $table->string('location');
            $table->longText('detail');
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('pic')->nullable();
            $table->string('phone')->nullable();
            $table->integer('zip')->nullable();
            $table->string('country');
            $table->timestamps();

            $table->foreign('operational_unit_id')->references('id')->on('operational_units')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
