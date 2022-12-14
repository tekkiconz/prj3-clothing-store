<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('category_id');
            $table->integer('sub_category_id');
            $table->integer('sub_sub_category_id')->nullable()->default(0);
            $table->integer('brand_id')->default(0);
            $table->integer('product_id');
            $table->integer('user_id')->comment = "customer_id";
            $table->integer('quantity');
            $table->double('selling_price');
            $table->double('buying_price');
            $table->double('total_buying_price');
            $table->double('total_selling_price');
            $table->double('unit_discount')->default(0);
            $table->double('total_discount')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
