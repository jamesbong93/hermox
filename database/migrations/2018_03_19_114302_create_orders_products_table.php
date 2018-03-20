<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->float('net_price', 8, 2);
            $table->float('list_price', 8, 2);
            $table->float('discount', 8, 2);
            $table->float('shipping_fee', 8, 2);
            $table->integer('user_id')->unsigned();
            $table->integer('shipping_country_id')->unsigned();
            $table->timestamps();
        });

        // set up orders reference key
        Schema::table('orders', function(Blueprint $table) {
           $table->foreign('shipping_country_id')->references('id')->on('countries')->onDelete('cascade');
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('orders_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('orders_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->timestamps();
        });

        // set up orders reference key
        Schema::table('orders_products', function(Blueprint $table) {
            $table->foreign('orders_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table) {
           $table->dropForeign('orders_shipping_country_id_foreign');
           $table->dropForeign('orders_user_id_foreign');
        });
        Schema::table('orders_products', function(Blueprint $table) {
           $table->dropForeign('orders_products_orders_id_foreign');
           $table->dropForeign('orders_products_product_id_foreign');
        });

        Schema::dropIfExists('orders');
        Schema::dropIfExists('orders_products');
    }
}
