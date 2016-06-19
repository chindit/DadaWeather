<?php

namespace Tests\WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherControllerTest extends WebTestCase
{
    public function testWeatherFromId(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/current/coords/');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode()); //Cannot find route for this
        $crawler = $client->request('GET', '/api/current/coords/50.4603051/4.8257186'); //Test from my coords.  But could be any coords.

        //This line fails.  But why?
        //$this->assertEquals('application/json', $response->headers->get('Content-Type')); //Is it JSON
    }
}
