<?php

use App\Enums\DeploymentStatus;
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
        Schema::create('deployments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket_id', 36)->nullable();
            $table->string('bast_number')->nullable();
            $table->string('deployment_number')->nullable();
            $table->string('holder_name')->nullable();
            $table->string('ritm_number')->nullable();
            $table->string('bast_date')->nullable();
            $table->string('bast_sign_date')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('staging_id')->nullable();
            $table->string('unit_serial');
            $table->string('delivery_id');
            $table->text('deployment_note')->nullable();
            $table->integer('status')->default(DeploymentStatus::PROCESSING->value);
            $table->string('sla')->nullable();
            $table->string('service_tag',)->nullable();
            $table->timestamp('end_contract')->nullable();
            $table->boolean('is_terminated')->default(false);
            $table->boolean('is_extended')->default(false);
            $table->timestamp('end_grace_period')->nullable();
            $table->timestamps();

            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('set null');
            $table->foreign('unit_serial')->references('serial')->on('units')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('staging_id')->references('id')->on('stagings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
