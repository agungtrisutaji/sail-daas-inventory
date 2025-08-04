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
        Schema::table('stagings', function (Blueprint $table) {
            $table->string('termination_id')->nullable();

            $table->foreign('termination_id')->references('id')->on('terminations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stagings', function (Blueprint $table) {
            $table->dropForeign('stagings_termination_id_foreign');

            $table->dropColumn('termination_id');
        });
    }
};
