<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employe_id')->unsigned();
            $table->unsignedInteger('user_member_id');
                $table->integer('rating');

                $table->string('comment');
            $table->foreign('employe_id')->references('id')->on('employes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_member_id')->references('id')->on('user_members');
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
        Schema::dropIfExists('ratings');
    }
}
