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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('delivery_number')->unique();
            $table->string('company_id')->nullable();
            $table->string('company_address')->nullable();
            $table->string('courier_id');
            $table->integer('category');
            $table->integer('status');
            $table->string('delivery_service_id');
            $table->string('tracking_number')->nullable();
            $table->timestamp('delivery_date');
            $table->timestamp('estimated_arrival_date')->nullable();
            $table->timestamp('actual_arrival_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('service_tag',)->nullable();
            $table->string('ritm_number')->nullable();
            $table->string('sla')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('company_address')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('courier_id')->references('id')->on('couriers')->onDelete('cascade');
            $table->foreign('delivery_service_id')->references('id')->on('delivery_services')->onDelete('cascade');
        });

        Schema::create('delivery_units', function (Blueprint $table) {
            $table->string('delivery_id');
            $table->string('unit_serial');
            $table->primary(['delivery_id', 'unit_serial']);
            $table->timestamps();

            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('unit_serial')->references('serial')->on('units')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_stagings');
        Schema::dropIfExists('delivery_units');
        Schema::dropIfExists('deliveries');
    }
};
