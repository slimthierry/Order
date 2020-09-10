<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index();
            $table->string('name');
            $table->integer('price')->nullable();
            $table->integer('order')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('product_variation_type_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('product_variation_type_id')->references('id')->on('product_variation_types');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
}
