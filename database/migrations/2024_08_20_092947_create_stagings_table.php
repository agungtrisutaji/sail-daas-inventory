<?php

use App\Enums\StagingStatus;
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
        Schema::create('stagings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('staging_number');
            $table->string('unit_serial');
            $table->string('batch_number')->nullable();
            $table->string('operational_unit_id')->nullable();
            $table->string('operational_unit_address')->nullable();
            $table->string('service_code')->nullable();
            $table->integer('request_category')->nullable();
            $table->timestamp('staging_start')->nullable();
            $table->timestamp('staging_finish')->nullable();
            $table->string('sla')->nullable();
            $table->string('staging_monitor')->nullable();
            $table->string('staging_monitor_model')->nullable();
            $table->string('company_id')->nullable();
            $table->string('company_address')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('note')->nullable();
            $table->integer('status')->default(StagingStatus::PROCESSING->value);
            $table->string('service_tag',)->nullable();
            $table->string('ritm_number')->nullable();
            $table->string('qc_by')->nullable();
            $table->boolean('is_deployed')->default(false);
            $table->string('req_id', 36)->nullable();
            $table->timestamps();

            $table->foreign('req_id')->references('id')->on('request_orders')->onDelete('set null');
            $table->foreign('operational_unit_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('operational_unit_address')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
            $table->foreign('service_code')->references('code')->on('services')->onDelete('set null');
            $table->foreign('unit_serial')->references('serial')->on('units')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('staging_monitor')->references('serial')->on('units')->onDelete('set null');
            $table->foreign('company_address')->references('id')->on('addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stagings');
    }
};
