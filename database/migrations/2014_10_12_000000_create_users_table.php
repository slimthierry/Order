<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('employe_id')->unsigned();
            // $table->integer('profil_id')->unsigned()->default(2);
            $table->unsignedInteger('payment_method_id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('username')->unique();
            $table->string('avatar')->default('user.jpg');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->float('train_distance')->default(0.00);
            $table->unsignedInteger('train_duration')->default(0);  //travel_time
            $table->integer('home_id')->unsigned()
            ->references('id')->on('train_stations')->default(0);

            $table->string('phonenumber', 20);
            $table->boolean('status')->default(1);
            $table->foreign('employe_id')->references('id')->on('employes');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
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
        Schema::dropIfExists('users');
    }
}
