<?php

class UsersTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create('ja_JP');

        DB::table('users')->truncate();

        // XXX: IMPORTANT - default password is admin
        $user = User::create(array(
            'email' => 'tanvir@amazinglife.jp',
            'password' => '$2y$10$6GJRcXsqtXZkss5zxCky6uhCCNtxrUiih2KeQJomc9V2R1rDqvGGW',
            'userType' => 1,
            "remember_token" => "",
            'createdBy' => 0,
            'updatedBy' => 0,
            'dateCreated' => $faker->dateTime(),
            'dateUpdated' => $faker->dateTime()
        ));

        $user = User::create(array(
            'email' => 'bando@amazinglife.jp',
            'password' => '$2y$10$6GJRcXsqtXZkss5zxCky6uhCCNtxrUiih2KeQJomc9V2R1rDqvGGW',
            'userType' => 1,
            "remember_token" => "",
            'createdBy' => 0,
            'updatedBy' => 0,
            'dateCreated' => $faker->dateTime(),
            'dateUpdated' => $faker->dateTime()
        ));

        $user = User::create(array(
            'email' => 'yu@amazinglife.jp',
            'password' => '$2y$10$6GJRcXsqtXZkss5zxCky6uhCCNtxrUiih2KeQJomc9V2R1rDqvGGW',
            'userType' => 1,
            "remember_token" => "",
            'createdBy' => 0,
            'updatedBy' => 0,
            'dateCreated' => $faker->dateTime(),
            'dateUpdated' => $faker->dateTime()
        ));

        $user = User::create(array(
            'email' => 'it@simpleso.jp',
            'password' => '$2y$10$6GJRcXsqtXZkss5zxCky6uhCCNtxrUiih2KeQJomc9V2R1rDqvGGW',
            'userType' => 2,
            "remember_token" => "",
            'createdBy' => 0,
            'updatedBy' => 0,
            'dateCreated' => $faker->dateTime(),
            'dateUpdated' => $faker->dateTime()
        ));

        $user = User::create(array(
            'email' => 'System',
            'password' => 'Not Available',
            'userType' => 0,
            "remember_token" => "",
            'createdBy' => 0,
            'updatedBy' => 0,
            'dateCreated' => $faker->dateTime(),
            'dateUpdated' => $faker->dateTime()
        ));
        
        DB::connection('aloha')->select(DB::raw("UPDATE users SET userId = '0' WHERE email = 'System'"));
    }
}
