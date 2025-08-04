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
        Schema::table('extends', function (Blueprint $table) {
            $table->integer('status')->default(0)->after('deployment_id');
            $table->string('ritm_number')->nullable()->after('deployment_id');
            $table->timestamp('end_contract')->nullable()->after('ritm_number');
            $table->timestamp('end_grace_period')->nullable()->after('end_contract');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extends', function (Blueprint $table) {
            $table->dropColumn('end_grace_period');
            $table->dropColumn('end_contract');
            $table->dropColumn('ritm_number');
            $table->dropColumn('status');
        });
    }
};
