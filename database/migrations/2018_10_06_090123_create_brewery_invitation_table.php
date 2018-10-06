<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreweryInvitationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_brewery_invitation', function (Blueprint $table) {
            $table->unsignedInteger ('breweryId')->nullable()->comment('Brewery by which user is invited to join');
            $table->unsignedInteger ('userId')->nullable()->comment('user who invite the user');
            $table->string ('token',50)->nullable()->comment('unique token as invitation-token');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_brewery_invitation');
    }
}
