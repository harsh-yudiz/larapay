<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->nullable();
            $table->string('price_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('description',500)->nullable();
            $table->string('billing_period')->nullable();
            $table->float('product_price')->nullable();
            $table->string('plan_id')->nullable();
            $table->enum('is_product',['stripe', 'paypal'])->nullable();
            $table->enum('is_plan',['stripe', 'paypal'])->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('products');
    }
}
