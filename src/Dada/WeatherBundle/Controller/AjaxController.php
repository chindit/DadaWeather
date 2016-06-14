<?php

namespace Dada\WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dada\WeatherBundle\Services\OpenWeather;

class AjaxController extends Controller
{
    public function getWeatherFromCoordsAction($latitude, $longitude){
        $owApi = $this->get('dada_weather.openweather');
        return $owApi->getWeatherFromCoords($latitude, $longitude);
    }
}
