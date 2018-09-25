<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersverificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_verification', function (Blueprint $table) {
            $table->unsignedInteger('userId')->nullabel()->comment('Reference from tbl_user.');
            $table->string ('type', 5)->nullable()->comment('Check verificatin token type as Email/Phone');
            $table->string ('token')->nullable()->comment('Set token for User Verification');
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
        Schema::dropIfExists('tbl_user_verification');
    }
}
