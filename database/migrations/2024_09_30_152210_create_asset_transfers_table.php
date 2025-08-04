<?php

use App\Enums\AssetTransferStatus;
use App\Enums\AssetTransferType;
use App\Enums\DocumentAvailability;
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
        Schema::create('asset_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('unit_serial');
            $table->string('status')->default(AssetTransferStatus::PROCESSING->value);
            $table->string('transfer_number')->nullable();
            $table->string('ritm_number')->nullable();
            $table->string('ticket_id')->nullable();
            $table->string('from_holder')->nullable();
            $table->string('to_holder')->nullable();
            $table->string('from_company_id')->nullable();
            $table->string('to_company_id');
            $table->string('from_location_id')->nullable();
            $table->string('to_location_id');
            $table->string('from_unit_id')->nullable();
            $table->string('to_unit_id')->nullable();
            $table->string('operational_unit_id')->nullable();
            $table->string('operational_unit_address')->nullable();
            $table->string('qc_by')->nullable();
            $table->boolean('qc_pass')->nullable();
            $table->timestamp('qc_date')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('finish_date')->nullable();
            $table->boolean('is_restaging')->nullable()->default(false);
            $table->integer('document_availability')->nullable()->default(DocumentAvailability::EXISTS->value);
            $table->integer('asset_transfer_type')->default(AssetTransferType::AFTER_DEPLOYMENT->value);
            $table->integer('transfer_for')->nullable()->default(AssetTransferType::AFTER_DEPLOYMENT->value);
            $table->string('transfer_remark')->nullable();

            $table->foreign('unit_serial')->references('serial')->on('units')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('from_company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('to_company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('from_location_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('to_location_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('from_unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('to_unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('operational_unit_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('operational_unit_address')->references('id')->on('addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_transfers');
    }
};
