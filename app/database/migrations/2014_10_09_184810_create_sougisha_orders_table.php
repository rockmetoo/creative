<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSougiShaOrdersTable extends Migration {

/**
 * Run the migrations.
 *
 * @return void
 */
    public function up()
    {
        Schema::create('sougishaOrders', function($t) {
            $t->bigInteger('userId')->unsigned();
            $t->bigInteger('orderId')->unsigned();
            $t->tinyInteger('productId')->unsigned()->default(1)->comment('1->シンプル火葬,2->シンプル葬');
            $t->tinyInteger('orderStatus')->unsigned()->default(1)->comment('1->got request,2->took,3->completed');
            $t->bigInteger('createdBy')->unsigned()->default(0);
            $t->bigInteger('updatedBy')->unsigned()->default(0);
            $t->dateTime('dateCreated')->default('0000-00-00 00:00:00');
            $t->dateTime('dateUpdated')->default('0000-00-00 00:00:00');
            
            $t->index(array('userId'), 'userId');
            $t->index(array('userId', 'productId'), 'userId+productId');
            $t->unique(array('orderId', 'productId'), 'orderId+productId');
            
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
        Schema::drop('sougishaOrders');
    }

}