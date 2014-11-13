<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration {

/**
 * Run the migrations.
 *
 * @return void
 */
    public function up()
    {
        Schema::create('profile', function($t) {
            $t->bigInteger('userId')->unsigned()->default(0);
            $t->string('firstName', 128)->default(null);
            $t->string('lastName', 128)->default(null);
            $t->string('postcode', 12)->default(null);
            $t->string('address', 128)->default(null);
            $t->bigInteger('createdBy')->unsigned()->default(0);
            $t->bigInteger('updatedBy')->unsigned()->default(0);
            $t->dateTime('dateCreated')->default('0000-00-00 00:00:00');
            $t->dateTime('dateUpdated')->default('0000-00-00 00:00:00');
            
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
        Schema::drop('profile');
    }

}
