<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilysoSodanTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('familyso')->create('sodan', function($t) {
            // auto increment id (primary key)
            $t->increments('id');
            $t->integer('subject')->unsigned();
            $t->integer('classification')->unsigned();
            $t->text('tag');
            $t->text('question');
            $t->text('answer');
            $t->integer('respondentId')->unsigned()->default(1);
            $t->bigInteger('numberOfPageView')->unsigned()->default('0')->comment('how many times user view this FAQ details');
            $t->bigInteger('answerSatisfiedYes')->unsigned()->default('0')->comment('increment it each time when click');
            $t->bigInteger('answerSatisfiedNo')->unsigned()->default('0')->comment('increment it each time when click');
            $t->integer('popularityRatio')->unsigned()->default('1')->comment('numberofClick increase this value');
            $t->tinyInteger('status')->unsigned()->default('1')->comment('0=>hide, 1=>show');
            $t->bigInteger('createdBy')->unsigned()->default(0);
            $t->bigInteger('updatedBy')->unsigned()->default(0);
            $t->dateTime('dateCreated')->default('0000-00-00 00:00:00');
            $t->dateTime('dateUpdated')->default('0000-00-00 00:00:00');

            $t->index(array('status', 'numberOfPageView'));

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
        Schema::connection('familyso')->drop('sodan');
    }

}
