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
        Schema::create('timeslots', function (Blueprint $table) {
            $table->string('timeslotID', 10)->primary();
            $table->time('startTime');
            $table->time('endTime');
            $table->date('date');
            $table->string('agentID', 10);
            $table->timestamps();
            // Define foreign key constraint
            $table->foreign('agentID')->references('agentID')->on('agents');
     
           
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeslots');
    }
};
