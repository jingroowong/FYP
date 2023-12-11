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
        Schema::create('property_photos', function (Blueprint $table) {
            $table->bigIncrements('propertyPhotoID');
            $table->string('propertyPath', 50);
            $table->dateTime('dateUpload');
            $table->string('propertyID', 10);
            $table->foreign('propertyID')->references('propertyID')->on('properties');
     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_photos');
    }
};
