<?php

use App\Enums\CompanyCategory;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('parent_id')->nullable();
            /**
             * TODO: INSURANCE: asuransi based on lising
             *  Asset transfer,  transfer data (bool, remark), restaging
             *  Termination (renewal, terminate only), if renewal proses unit baru dulu sampai deploy, pengecekan kelengkapan dan spesifikasi unit terminate. checklist terminate
             **/
            $table->string('company_group')->nullable();
            $table->string('company_code')->unique()->nullable();
            $table->string('company_name');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_category')->default(CompanyCategory::CUSTOMER->value);
            $table->boolean('is_service_center')->nullable()->default(false);
            $table->string('service_category')->nullable();
            $table->timestamps();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
