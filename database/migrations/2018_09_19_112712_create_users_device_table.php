<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_device_detail', function (Blueprint $table) {
            $table->unsignedInteger('userId')->nullabel()->comment('Reference from tbl_user.');
            $table->text('deviceDetail')->nullable()->comment('store the device detail of the user.fcmId, deviceId, etc');
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
        Schema::dropIfExists('tbl_user_device_detail');
    }
}
