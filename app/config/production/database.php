<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => array(

        'default' => 'schooler',
        
        'schooler' => array(
            'driver'    => 'mysql',
            'host'      => 'al.cgh8hcs3q4nc.ap-northeast-1.rds.amazonaws.com',
            'database'  => 'schooler',
            'username'  => 'rootcock',
            'password'  => 'goTAKEASS&%!*M',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ),
        'schoolerUsers' => array(
            'driver'    => 'mysql',
            'host'      => 'al.cgh8hcs3q4nc.ap-northeast-1.rds.amazonaws.com',
            'database'  => 'schoolerUsers',
            'username'  => 'rootcock',
            'password'  => 'goTAKEASS&%!*M',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        )
    ),

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => array(

        'cluster' => false,

        'default' => array(
            'host'     => 'al-redis.k9erak.0001.apne1.cache.amazonaws.com',
            'port'     => 6379,
            'database' => 0,
        ),

    ),
);
