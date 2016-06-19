<?php

namespace Tests\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Test\TypeTestCase;

class FrontControllerTest extends WebTestCase
{
    /**
     * Test front home page.  Nothing special, just checking the route
     */
    public function testIndex(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('DadaWeather', $crawler->filter('#site-title')->text());
    }

    /**
     * Test if search form is working properly
     */
    public function testSearch(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/'); //Form is in header in frontpage
        $form = $crawler->selectButton('search')->form(array('town_name' => 'Bruxelles')); //Setting default value

        $crawler = $client->submit($form); //Getting form
        $this->assertEquals(200, $client->getResponse()->getStatusCode()); //Checking form submission
        //$this->assertGreaterThan(5, strlen($crawler->filter('#town-weather')->text())); //Checking if info is correctly retrieved (if info is OK, there is a small text here)
        $this->assertEquals('Arrondissement Brussel', $crawler->filter('#town-name')->text());
    }
}