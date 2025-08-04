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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pr_number');
            $table->string('po_number');
            $table->date('po_date');
            $table->string('customer_id');
            $table->string('unit_serial');
            $table->integer('quantity');
            $table->string('unit_price');
            $table->string('total_price');
            $table->string('note')->nullable();
            $table->string('service_code')->nullable();
            $table->string('company_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
