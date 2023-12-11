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
        

Schema::create('agents', function (Blueprint $table) {
    $table->string('agentID', 10)->primary();
    $table->string('agentName', 40);
    $table->string('agentPhone', 12);
    $table->string('agentEmail', 50)->unique();
    $table->string('password', 255);  
    $table->string('photo')->nullable();
    $table->string('licenseNum',10);
    $table->timestamp('lastLogin')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamp('registerDate')->nullable();
    $table->timestamp('updateAt')->nullable();
    $table->rememberToken();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
