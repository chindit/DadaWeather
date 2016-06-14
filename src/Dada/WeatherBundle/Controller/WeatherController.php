<?php
/**
 * DadaWeather : Copyright © 2016 Chindit
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * First generated : 06/14/2016 at 15:56
 */

namespace Dada\WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WeatherController extends Controller{
    public function showWeatherFromIdAction($id){
        $owApi = $this->get('dada_weather.openweather');
        $result = $owApi->getWeatherFromId($id);
        return $this->render('DadaCoreBundle::index.html.twig', array('weather' => $result));
    }

}