<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSougiShaProfileTable extends Migration {

/**
 * Run the migrations.
 *
 * @return void
 */
    public function up()
    {
        Schema::create('sougishaProfile', function($t) {
            $t->bigInteger('userId')->unsigned()->default(0)->unique();
            $t->string('companyName', 255)->default(null)->nullable();
            $t->string('postcode', 12)->default(null)->nullable();
            $t->string('companyAddress', 255)->default(null)->nullable();
            $t->string('companyLogo', 255)->default(null)->nullable();
            $t->string('primaryCellPhone', 16)->default(null)->nullable();
            $t->string('primaryEmail', 255)->default(null)->nullable();
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
        Schema::drop('sougishaProfile');
    }

}
