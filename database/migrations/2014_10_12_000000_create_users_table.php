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
            $table->string('firsName');
            $table->string('lastName');
            $table->string('avatarUrl')->nullable();
            $table->string('email')->unique();
            $table->integer('countryCode')->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->integer('verifyCode')->nullable();
            $table->timestamp('registerAt')->nullable();
            $table->string('deviceId')->nullable();
            $table->string('ipAddress')->nullable();
            $table->integer('isActived')->nullable();
            $table->integer('phone_number')->nullable();
            $table->integer('phoneIsVerify')->nullable();
            $table->integer('phoneIsVerifyAt')->nullable();
            $table->integer('emailIsVerify')->nullable();
            $table->timestamp('emailIsVerifyAt')->nullable();
            $table->string('password');
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
        Schema::dropIfExists('users');
    }
}
