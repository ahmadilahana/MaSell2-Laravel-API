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
            $table->string('username')->nullable();
            $table->string('avatarUrl')->nullable();
            $table->string('email')->unique();
            $table->integer('emailVerify')->nullable();
            $table->integer('emailIsVerifyAt')->nullable();
            $table->string('emailVerifyId')->nullable();
            $table->integer('emailVerifyIdExpired')->nullable();
            $table->string('password');
            $table->string('countryCode')->nullable();
            $table->string('country')->nullable();
            $table->integer('isActived')->nullable();
            $table->string('deviceId')->nullable();
            $table->integer('registerAt')->nullable();
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
