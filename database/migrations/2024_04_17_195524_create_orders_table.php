<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable();
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('street');
            $table->string('num');
            $table->string('city');
            $table->string('zip');
            $table->string('shipping_type_id');
            $table->string('payment_type');
            $table->decimal('price', 8, 2);
            $table->string('note', 400)->nullable();
            $table->timestamps();
        });

        Schema::create('order_product', function (Blueprint $table) {
            $table->uuid('order_id');
            $table->uuid('product_id');
            $table->integer('priceAtPurchace');
            $table->integer('quantity');
            $table->primary(['order_id', 'product_id']);
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('order_product');
        Schema::dropIfExists('orders');
    }
}
