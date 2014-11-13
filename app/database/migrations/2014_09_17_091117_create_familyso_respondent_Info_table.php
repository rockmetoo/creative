<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilysoRespondentInfoTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('familyso')->create('respondentInfo', function($t) {
            // auto increment id (primary key)
            $t->increments('id');

            $t->string('respondentName', 255);
            $t->smallInteger('respondentCompany')->unsigned();
            $t->string('respondentTitle', 255);
            $t->text('respondentPRText');
            $t->bigInteger('createdBy')->unsigned()->default(0);
            $t->bigInteger('updatedBy')->unsigned()->default(0);
            $t->dateTime('dateCreated');
            $t->dateTime('dateUpdated');

            $t->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('familyso')->drop('respondentInfo');
    }

}
