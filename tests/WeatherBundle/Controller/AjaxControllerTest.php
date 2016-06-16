<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 16/06/2016
 * Time: 20:36
 */

namespace Tests\WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxControllerTest extends WebTestCase
{
    public function testWeatherFromCoords(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/current/by-id/2510911'); //ID is for Sevilla
        $this->assertEquals(200, $client->getResponse()->getStatusCode()); //Is response OK ?
        $this->assertContains('Sevilla', $crawler->filter('#town-name')->text()); //Is data OK ?
    }
}
