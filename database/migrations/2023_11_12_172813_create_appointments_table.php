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
        Schema::create('appointments', function (Blueprint $table) {
            $table->string('appID', 10)->primary();
            $table->string('status', 20);
            $table->string('propertyID', 10);
            $table->string('tenantID', 10);
            $table->string('timeslotID', 10);
            $table->timestamps();
           
            // Define foreign key constraints
            $table->foreign('propertyID')->references('propertyID')->on('properties');
            $table->foreign('tenantID')->references('tenantID')->on('tenants');
            $table->foreign('timeslotID')->references('timeslotID')->on('timeslots');
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
