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
        Schema::create('refunds', function (Blueprint $table) {
            $table->string('refundID', 10)->primary();
            $table->decimal('refundAmount', 7, 2);
            $table->date('refundDate');
            $table->string('refundReason', 255);
            $table->string('refundStatus', 20);
            $table->date('approvalDate')->nullable();
            $table->string('rejectReason', 255)->nullable();
            $table->string('propertyRentalID', 10);


            $table->foreign('propertyRentalID')->references('propertyRentalID')->on('property_rentals');
       
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
