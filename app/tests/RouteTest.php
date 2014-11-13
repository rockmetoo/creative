<?php

class RouteTest extends TestCase
{

/*
 * Routing Test
 *
 * @return void
 */

    public function testRouteTop()
    {
        $this->call('GET', '/');
        $this->assertResponseOk();
    }

    public function testRouteFeature()
    {
        $this->call('GET', '/feature');
        $this->assertResponseOk();
    }

    public function testRouteFlow()
    {
        $this->call('GET', '/flow');
        $this->assertResponseOk();
    }

    public function testRouteReservationNumber()
    {
        $this->call('GET', '/reservation');
        $this->assertResponseOk();
    }

    public function testRouteContact()
    {
        $this->call('GET', '/contact');
        $this->assertResponseOk();
    }

    public function testRouteHallPrefecture()
    {
        $this->call('GET', '/halls/prefectures');
        $this->assertResponseOk();
    }

    public function testRouteArea()
    {
        $this->call('GET', '/area');
        $this->assertResponseOk();
    }

    public function testGetContact()
    {
        $this->call('GET', '/contact');
        $this->assertResponseOk();
    }

    public function testGetContactThanks()
    {
        $this->call('GET', '/contact/thanks');
        $this->assertResponseStatus(302);
    }

    public function testPostContactWithEmptyData()
    {
        // IMPORTANT TODO
    }

    public function testPostContactWithValidationOK()
    {
        // IMPORTANT TODO
    }

    public function testPostContactWithValidationFail()
    {
        // IMPORTANT TODO
    }
}
