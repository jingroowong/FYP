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
        Schema::create('wallets', function (Blueprint $table) {
            $table->string('walletID', 10)->primary();
            $table->integer('pinNumber');
            $table->decimal('balance', 7, 2);
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
        Schema::dropIfExists('wallets');
    }
};
