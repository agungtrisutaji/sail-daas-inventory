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
        Schema::create('terminations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket_id')->nullable();
            $table->string('jarvis_ticket')->nullable();
            $table->string('tracking_number')->nullable();
            $table->boolean('unit_returned')->default(false);
            $table->integer('return_status');
            $table->string('termination_number');
            $table->timestamp('terminated_date')->nullable();
            $table->string('terminated_id');
            $table->integer('termination_type')->nullable();
            $table->timestamp('renewal_date')->nullable();
            $table->timestamp('end_contract_date')->nullable();
            $table->integer('status');
            $table->string('termination_remark')->nullable();
            $table->timestamps();

            $table->foreign('terminated_id')->references('id')->on('deployments')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminations');
    }
};
