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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->string('transactionID', 10)->primary();
            $table->string('transactionType', 20);
            $table->date('transactionDate');
            $table->time('transactionTime');
            $table->decimal('transactionAmount', 7, 2);
            $table->string('walletID', 10);
            $table->timestamps();
            // Define foreign key constraint
            $table->foreign('walletID')->references('walletID')->on('wallets');
   
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
