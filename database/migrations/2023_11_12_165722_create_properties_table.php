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
        Schema::create('properties', function (Blueprint $table) {
            $table->string('propertyID', 10)->primary();
            $table->string('propertyName', 40);
            $table->string('propertyDesc', 150);
            $table->string('propertyType', 20);
            $table->string('propertyAddress', 100);
            $table->boolean('propertyAvailability');
            $table->integer('bedroomNum', false, true);
            $table->integer('bathroomNum', false, true);
            $table->integer('buildYear', false, true)->nullable();
            $table->integer('squareFeet', false, true)->nullable();
            $table->string('furnishingType', 50);
            $table->decimal('rentalAmount', 7, 2);
            $table->decimal('depositAmount', 7, 2)->nullable();
            $table->string('agentID', 10);
            $table->foreign('agentID')->references('agentID')->on('agents');
            $table->unsignedInteger('stateID');
            $table->foreign('stateID')->references('stateID')->on('states');
     
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
