<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Seller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller', function (Blueprint $table) {
            $table->id('sellerId');
            $table->string('username');
            $table->string('avatarUrl')->nullable();
            $table->string('email')->unique();
            $table->integer('emailVerify')->nullable();
            $table->timestamp('emailIsVerifyAt')->nullable();
            $table->string('emailVerifyId')->nullable();
            $table->string('emailVerifyIdExpired')->nullable();
            $table->string('password');
            $table->integer('countryCode')->nullable();
            $table->string('country')->nullable();
            $table->integer('isActived')->nullable();
            $table->string('deviceId')->nullable();
            $table->timestamp('registerAt')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('seller');
    }
}
