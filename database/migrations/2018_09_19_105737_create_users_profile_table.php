<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_profile', function (Blueprint $table) {
            $table->unsignedInteger('userId')->nullabel()->comment('Reference from tbl_user.');
            $table->string('firstName',100)->nullable()->comment('first name of the user.');
            $table->string('lastName',100)->nullable()->comment('last name of the user.');
            $table->string('mobile',15)->nullable()->comment('mobile number of the user. must be unique.');
            $table->unsignedMediumInteger ('countryId')->nullable()->comment('Set country Id for address');
            $table->string ('address')->nullable ()->comment('address of the user');
            $table->string ('postCode', 8)->nullable ()->comment('postal-code of the user address.');
            $table->string('image')->nullable()->comment('avatar of the user.');
            $table->boolean('mobile_verified')->nullable()->comment('check if mobile number is verified or not');
            $table->boolean('email_verified')->nullable()->comment('check if email is verified or not');
            $table->softDeletes();
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
        Schema::dropIfExists('tbl_user_profile');
    }
}
