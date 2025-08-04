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
        Schema::create('unit_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('document_number')->unique();
            $table->string('company_id')->nullable();
            $table->string('company_address')->nullable();
            $table->string('buyer_name');
            $table->string('sales_name');
            $table->timestamp('sold_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('company_address')->references('id')->on('addresses')->onDelete('set null');
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->string('sale_id');
            $table->string('unit_serial');
            $table->primary(['sale_id', 'unit_serial']);
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('unit_sales')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('unit_serial')->references('serial')->on('units')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('unit_sales');
    }
};
