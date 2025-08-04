<?php

use App\Enums\TicketStatus;
use App\Enums\TicketType;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket_number')->unique();
            $table->string('unit_serial');
            $table->string('jarvis_ticket')->nullable();
            $table->string('company_id');
            $table->string('company_address')->nullable();
            $table->string('caller'); //Ticket Creator
            $table->string('requestor'); //Ticket Requestor (holder_name)
            $table->integer('type')->default(TicketType::REQUEST);
            $table->integer('status')->default(TicketStatus::NEW);
            $table->string('remarks')->nullable();
            $table->string('service_tag',)->nullable();
            $table->timestamps();

            $table->foreign('unit_serial')->references('serial')->on('units')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('company_address')->references('id')->on('addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
