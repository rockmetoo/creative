<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilysoOrdersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('familyso')->create('orders', function($t) {
                $t->bigIncrements('orderId');
                $t->string('orderNumber')->nullable();
                $t->tinyInteger('paymentGateway')->unsigned()->nullable();
                $t->string('paypalTxId')->nullable();
                $t->string('paypalPayerEmail')->nullable();
                $t->string('webpayChargeId')->nullable();
                $t->string('phoneId', 12)->unique();
                $t->string('nameOfTheDeceasedPerson')->nullable();
                $t->smallInteger('deceasedPersonGender')->unsigned()->default(1);
                $t->string('hospitalName')->nullable();
                $t->tinyInteger('enshrinedLocation')->unsigned()->nullable();
                $t->smallInteger('hospitalPrefecture')->unsigned();
                $t->string('hospitalCity')->nullable();
                $t->integer('additionalFlower1')->unsigned()->default(0);
                $t->integer('additionalFlower2')->unsigned()->default(0);
                $t->integer('monk')->unsigned()->default(0);
                $t->integer('photoSession')->unsigned()->default(0);
                $t->integer('posthumousName')->unsigned()->default(0);
                $t->integer('total')->unsigned()->default(228000);
                $t->tinyInteger('isPreorder')->default(0);
                $t->bigInteger('preorderReservation')->unsigned()->nullable();
                $t->tinyInteger('status')->unsigned()->default(1);
                $t->tinyInteger('notificationStatus')->unsigned()->default(0);
                $t->dateTime('dateCreated');
                $t->dateTime('dateUpdated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
