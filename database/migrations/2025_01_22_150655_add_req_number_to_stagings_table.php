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
            $table->string('req_number')->nullable();
            $table->string('rtim_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stagings', function (Blueprint $table) {
            $table->dropColumn('req_number');
            $table->dropColumn('rtim_number');
        });
    }
};
