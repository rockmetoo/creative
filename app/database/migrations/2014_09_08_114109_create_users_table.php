<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

/**
 * Run the migrations.
 *
 * @return void
 */
    public function up()
    {
        Schema::create('users', function($t) {
            $t->bigIncrements('userId')->unsigned();
            $t->string('email', 255)->unique();
            $t->string('password', 128);
            $t->smallInteger('userType')->unsigned()->default(1)->comment('1->admin, 2->sougisha');
            $t->tinyInteger('userStatus')->unsigned()->default(1)->comment('0->Registration Not Confirmed,1->Active,2->Frozen');
            $t->string('remember_token', 100);
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
        Schema::drop('users');
    }

}
