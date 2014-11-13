<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForgotPasswordVerifyTable extends Migration {

/**
 * Run the migrations.
 *
 * @return void
 */
    public function up()
    {
        Schema::create('forgotPasswordVerify', function($t) {
            $t->bigInteger('userId')->unsigned()->default(0);
            $t->string('email', 255)->unique();
            $t->string('code', 128)->default(null);
            $t->bigInteger('createdBy')->unsigned()->default(0);
            $t->bigInteger('updatedBy')->unsigned()->default(0);
            $t->dateTime('dateCreated')->default('0000-00-00 00:00:00');
            $t->dateTime('dateUpdated')->default('0000-00-00 00:00:00');
            
            $t->index(array('userId'), 'userId');
            
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
        Schema::drop('forgotPasswordVerify');
    }

}
