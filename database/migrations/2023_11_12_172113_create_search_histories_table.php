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
        Schema::create('search_histories', function (Blueprint $table) {
            $table->string('searchID', 10)->primary();
            $table->dateTime('searchDate');
            $table->unsignedSmallInteger('clickTime');
            $table->string('tenantID', 10);
            $table->string('propertyID', 10);
            $table->timestamps();
            // Define foreign key constraints
            $table->foreign('tenantID')->references('tenantID')->on('tenants');
            $table->foreign('propertyID')->references('propertyID')->on('properties');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_histories');
    }
};
