<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTable extends Migration {

/**
 * Run the migrations.
 *
 * @return void
 */
    public function up()
    {
        Schema::connection('knowledge')->create('coupon', function($t) {
            $t->bigIncrements('id')->unsigned();
            $t->string('couponCode', 32)->unique();
            $t->smallInteger('product')->unsigned()->default(1)->comment('1->シンプル火葬, 2->シンプル葬, 3->Both');
            $t->smallInteger('percentage')->unsigned()->default(1);
            $t->string('description', 255)->nullable();
            $t->Integer('startDate')->unsigned()->nullable();
            $t->tinyInteger('isActive')->unsigned()->default(1)->comment('0->inactive, 1->active');
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
        Schema::connection('knowledge')->drop('coupon');
    }

}
