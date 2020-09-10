<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_id')->unsigned();
            $table->integer('user_member_id')->unsigned();
            $table->string('name');
            $table->string('phonenumber', 20);
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('address_id')->references('id')
                ->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_member_id')->references('id')
                ->on('user_members')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
