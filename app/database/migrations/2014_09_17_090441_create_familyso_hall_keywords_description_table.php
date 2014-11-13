<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilysoHallKeywordsDescriptionTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('familyso')->create('hallKeywordsDescription', function($t) {
            $t->bigInteger('id');

            $t->text('title');
            $t->text('keywords');
            $t->text('descriptions');
            $t->bigInteger('createdBy')->unsigned()->default(0);
            $t->bigInteger('updatedBy')->unsigned()->default(0);
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
        Schema::connection('familyso')->drop('hallKeywordsDescription');
    }

}
