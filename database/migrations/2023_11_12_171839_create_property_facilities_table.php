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
        Schema::create('property_facilities', function (Blueprint $table) {
            $table->string('propertyID', 10);
            $table->string('facilityID', 6);
           
            $table->primary(['propertyID', 'facilityID']);
           
            $table->foreign('propertyID')->references('propertyID')->on('properties');
            $table->foreign('facilityID')->references('facilityID')->on('facilities');
     
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_facilities');
    }
};
