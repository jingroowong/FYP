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
    {Schema::create('tenants', function (Blueprint $table) {
        $table->string('tenantID', 10)->primary();
        $table->string('tenantName', 40);
        $table->string('tenantEmail', 50)->unique();
        $table->string('tenantPhone', 12);
        $table->string('password', 255);  
        $table->string('photo')->nullable();
        $table->string('tenantDOB',10);
        $table->char('gender', 1);
        $table->timestamp('lastLogin')->nullable();
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
        Schema::dropIfExists('tenants');
    }
};
