<?php

class AclTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create('ja_JP');

        DB::table('acl')->truncate();

        $acl = User::create(array(
            'userId' => 1,
            'acl' => '["AdminController", "SougiShaController"]',
            'dateCreated' => $faker->dateTime(),
            'dateUpdated' => $faker->dateTime()
        ));
        
        $acl = User::create(array(
            'userId' => 2,
            'acl' => '["AdminController", "SougiShaController"]',
            'dateCreated' => $faker->dateTime(),
            'dateUpdated' => $faker->dateTime()
        ));
        
        $acl = User::create(array(
            'userId' => 3,
            'acl' => '["AdminController", "SougiShaController"]',
            'dateCreated' => $faker->dateTime(),
            'dateUpdated' => $faker->dateTime()
        ));
    }
}
