<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid("categoryId")->nullable();
            $table->uuid("manufacturerId")->nullable();
            $table->string('featuredImage');
            $table->string('title', 48);
            $table->string('slug', 48)->unique();
            $table->string('shortDescription', 200)->nullable();
            $table->text('longDescription', 2000)->nullable();
            $table->float('price');
            $table->enum('availability', ['IN_STOCK', 'IN_SHOP', 'OUT_OF_STOCK'])->default("IN_STOCK");
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
