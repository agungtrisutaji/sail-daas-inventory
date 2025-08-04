<?php

use App\Enums\RequestUpgradeStatus;
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
        Schema::create('request_upgrades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket')->nullable();
            $table->string('operational_unit_id')->nullable();
            $table->string('operational_unit_address')->nullable();
            $table->dateTime('bast_date')->nullable();
            $table->decimal('offering_price', 8, 2);
            $table->decimal('expense_part', 8, 2);
            $table->decimal('expense_engineer', 8, 2);
            $table->decimal('expense_delivery', 8, 2);
            $table->decimal('expense_total', 8, 2)->nullable();
            $table->integer('status')->default(RequestUpgradeStatus::OFFERING->value);
            $table->string('engineer', 100)->nullable();
            $table->string('ritm_number')->nullable();
            $table->string('service_tag')->nullable();
            $table->timestamps();

            $table->foreign('ticket')->references('ticket_number')->on('tickets')->onDelete('set null');
            $table->foreign('operational_unit_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('operational_unit_address')->references('id')->on('addresses')->onDelete('set null');
        });

        Schema::create('upgrade_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('request_upgrade_id');
            $table->enum('upgrade_type', ['SSD', 'HDD', 'RAM']);
            $table->text('upgrade_remark')->nullable();
            $table->timestamps();

            $table->foreign('request_upgrade_id')->references('id')->on('request_upgrades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upgrade_details');
        Schema::dropIfExists('request_upgrades');
    }
};
