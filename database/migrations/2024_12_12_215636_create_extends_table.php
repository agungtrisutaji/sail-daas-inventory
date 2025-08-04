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
        Schema::create('extends', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('unit_serial');
            $table->string('deployment_id');
            $table->timestamps();

            $table->foreign('deployment_id')->references('id')->on('deployments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extends');
    }
};
