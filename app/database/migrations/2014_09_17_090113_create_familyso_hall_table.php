<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilysoHallTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('familyso')->create('hall', function($t) {
            $t->increments('id');

            $t->smallInteger('state');
            $t->mediumInteger('city')->unsigned();
            $t->string('funeralName');
            $t->string('funeralName2');
            $t->string('funeralNameKana');
            $t->mediumInteger('numberOfSeatsMax')->unsigned();
            $t->mediumInteger('numberOfSeatsMin')->unsigned()->default(1);
            $t->smallInteger('seatsType')->unsigned();
            $t->smallInteger('operation');
            $t->string('operation2');
            $t->tinyInteger('enshrined')->unsigned();
            $t->tinyInteger('coldStorage')->unsigned();
            $t->string('emergencyEnshrined');
            $t->string('telephone', 128);
            $t->mediumInteger('parking')->unsigned();
            $t->tinyInteger('lodging')->unsigned();
            $t->string('accommodationRemarks');
            $t->integer('rate')->unsigned();
            $t->string('rateRemarks');
            $t->tinyInteger('ev')->unsigned();
            $t->tinyInteger('disabled')->unsigned();
            $t->string('firstSevenDays');
            $t->string('sectionName');
            $t->string('userLimitAndRestrictions');
            $t->tinyInteger('funeralMusic')->unsigned();
            $t->string('funeralMusicSupplement');
            $t->string('crematoriumInfo');
            $t->string('cremationGenaralFee');
            $t->tinyInteger('publicCoffin')->unsigned();
            $t->tinyInteger('altar')->unsigned();
            $t->tinyInteger('depositRemains')->unsigned();
            $t->string('postCode', 10);
            $t->smallInteger('locationKen')->unsigned();
            $t->string('locationShi', 32);
            $t->string('locationRoad');
            $t->double('longitude');
            $t->double('latitude');
            $t->string('locationRoute');
            $t->string('locationNearestStation');
            $t->string('locationNearestStationRemarks');
            $t->string('locationRoute2');
            $t->string('locationNearestStation2');
            $t->string('locationNearestStationRemarks2');
            $t->string('locationRoute3');
            $t->string('locationNearestStation3');
            $t->string('locationNearestStationRemarks3');
            $t->string('fax', 16);
            $t->smallInteger('contractorSpec')->unsigned();
            $t->smallInteger('mourners');
            $t->string('seatPositioning', 64);
            $t->string('japaneseStyleRoom');
            $t->string('overAllRemarks');
            $t->bigInteger('createdBy')->unsigned()->default(0);
            $t->bigInteger('updatedBy')->unsigned()->default(0);
            $t->dateTime('dateCreated');
            $t->dateTime('dateUpdated');

            $t->index('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('familyso')->drop('hall');
    }

}
