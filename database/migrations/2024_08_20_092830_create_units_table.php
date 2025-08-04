<?php

use App\Enums\AssetGroup;
use App\Enums\UnitStatus;
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
        Schema::create('units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('serial')->unique();
            $table->string('distributor_id')->nullable();
            $table->string('ticket_number')->nullable();
            $table->string('project')->nullable();
            $table->string('monitor')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('service_category')->nullable();
            $table->string('note')->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->timestamp('receive_date')->nullable();
            $table->string('receive_number')->nullable();
            $table->string('category');
            $table->integer('status')->default(UnitStatus::AVAILABLE->value);
            $table->integer('asset_group')->default(AssetGroup::DAAS->value);
            $table->boolean('isurance')->default(false);
            $table->string('isurance_type')->nullable();
            $table->boolean('internal_use')->default(false);
            $table->boolean('is_backup')->default(false);
            $table->boolean('is_refurbished')->default(false);
            $table->timestamps();

            $table->foreign('distributor_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('isurance_type')->references('id')->on('insurances')->onDelete('set null');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->foreign('monitor')->references('serial')->on('units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
