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
        Schema::create('request_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('holder_name');
            $table->string('po_number');
            $table->string('company_id', 36)->nullable();
            $table->string('company_address', 36)->nullable();
            $table->timestamp('open_at')->nullable();
            $table->string('service_category', 36)->nullable();
            $table->string('service_id', 36)->nullable();
            $table->timestamp('request_date')->nullable();

            $table->timestamps();
        });

        Schema::create('request_items', function (Blueprint $table) {
            $table->string('req_id');
            $table->string('unit_serial');
            $table->primary(['req_id', 'unit_serial']);

            $table->foreign('req_id')->references('id')->on('request_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_items');
        Schema::dropIfExists('request_orders');
    }
};
