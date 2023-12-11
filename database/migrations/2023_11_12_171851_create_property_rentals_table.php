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
        Schema::create('property_rentals', function (Blueprint $table) {
            $table->string('propertyRentalID', 10)->primary();
            $table->date('date')->nullable();
            $table->string('propertyID', 10);
            $table->string('paymentID', 10);
            $table->string('tenantID', 10);


            $table->foreign('propertyID')->references('propertyID')->on('properties');
            $table->foreign('paymentID')->references('paymentID')->on('payments');
            $table->foreign('tenantID')->references('tenantID')->on('tenants');
     
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_rentals');
    }
};
