<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_users', function (Blueprint $table) {
            $table->unsignedInteger('userId')->nullable()->comment('Reference from tbl_users');
            $table->unsignedInteger('updatedBy')->nullable()->comment('Reference from tbl_users');
            $table->text ('changeset')->nullable()->comment ('Find updated fields');
            $table->text ('previousState')->nullable()->comment ('previus fields values');
            $table->text ('newState')->nullable()->comment ('new fields values');
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
        Schema::dropIfExists('log_users');
    }
}
