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
        Schema::create('reviews', function (Blueprint $table) {
            $table->string('reviewID', 10)->primary();
            $table->string('comment', 200);
            $table->tinyInteger('rating')->unsigned();
            $table->timestamp('reviewDate');
	        $table->string('reviewerID',10);
            $table->string('reviewItemID', 10);
            $table->string('ParentReviewID', 10)->nullable();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
