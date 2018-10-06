<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email',255)->nullable()->comment('Email to login user. must be unique');
            $table->string('password',255)->nullable()->comment('Password to login user.');
            $table->string('role',20)->nullable()->comment('Role of the user.');
            $table->boolean('status')->nullable()->comment ('Boolean:set status as active/inactive of the user. 1=>active, 0=>in-active');
            $table->string('reason')->nullable()->comment('reason behind to update status of user. set by admin only');
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
        Schema::dropIfExists('tbl_users');
    }
}
