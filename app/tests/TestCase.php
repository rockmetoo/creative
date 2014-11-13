<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected static $migrated = null;

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__.'/../../bootstrap/start.php';
    }

    public function setUp()
    {
        parent::setUp();
        if (is_null(self::$migrated)) {
            $this->setUpDb();
        }
    }

    protected function tearDown()
    {
        \Mockery::close();
    }

    public function setUpDb()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');
        self::$migrated = true;
    }

}
