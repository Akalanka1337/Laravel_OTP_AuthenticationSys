<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('exp_time');
            $table->unsignedBigInteger('phone_number');
            $table->unsignedBigInteger('sms_response')->nullable();
            $table->string('sms_id')->unique();
            $table->string('project', 255);
            $table->string('desc', 255);
            $table->string('auth_status')->nullable();
            $table->string('otp');
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
        Schema::dropIfExists('otps');
    }
}
