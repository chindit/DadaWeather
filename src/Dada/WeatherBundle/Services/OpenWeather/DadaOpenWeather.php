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
 * First generated : 06/06/2016 at 19:04
 */

namespace Dada\WeatherBundle\Services\OpenWeather;

use Symfony\Component\HttpFoundation\Response;

class DadaOpenWeather{

    private $api;
    private $args;
    private $apiUrl = 'http://api.openweathermap.org/data/2.5/weather';

    /**
     * DadaOpenWeather constructor. Basic constructor.  Args can be send by calling «setParam()» method
     * @param $api : API key for OpenWeather
     */
    public function __construct($api){
        $this->api = $api;
    }

    /**
     * Get CURRENT weather form coordinates (Longitude and Latitude)
     *
     * @param $latitude : float
     * @param $longitude : float
     * @return Response : Return a JSON response
     */
    public function getWeatherFromCoords($latitude, $longitude){
        $apiUrl = '?lat='.$latitude.'&lon='.$longitude;
        $response = $this->getAnswerFromApi($apiUrl);
        $ajaxResponse = new Response();
        $ajaxResponse->setContent(json_encode($response)); //We need to re-encode as JSON because JS is expecting it
        return $ajaxResponse;
    }


    /**
     * Get CURRENT weather for a city ID
     *
     * @param $id : should be valid, it'd work better ;)
     * @throws \InvalidArgumentException : in case ID is not numeric or < 0
     * @return JSON value
     */
    public function getWeatherFromId($id){
        if(!is_numeric($id) || $id < 0)
            throw new \InvalidArgumentException('Expecting positive integer value');
        $apiUrl = 'id='.$id;
        return $apiResponse = $this->getAnswerFromApi($apiUrl);
    }


    /**
     * Setup functions.  Used when initializing service to add some args to any API call.  This function is NOT mandatory
     *
     * @param $args : array with key/values
     *
     */
    public function setParams($args){
        if(!is_array($args))
            throw new \InvalidArgumentException('Expected Array value. Got '.gettype($args).' instead');
        $string = '';
        foreach($args as $key => $value){
            $string .= '&'.$key.'='.$value;
        }
        $this->args = $string;
    }

    /**
     * Contact API and return the response
     *
     * @param $url : URL to contact (without API url and args)
     * @return array : API response in array
     * @throws \NotFoundHttpException : in case of no return from the API
     */
    private function getAnswerFromApi($url){
        $contactUrl = $this->apiUrl.(($url[0] == '?') ? '' : '?').$url.$this->args.'&appid='.$this->api;
        if(filter_var($contactUrl, FILTER_VALIDATE_URL) === false)
            throw new \InvalidArgumentException('Unable to contact the API.  URL appears to be malformed');
        $apiAnswer = file_get_contents($contactUrl);
        if(empty($apiAnswer))
            throw new \NotFoundHttpException('Ooops… It seems the query you did to the API didn\'t show any answer.  Sad day, eh?');

        //We automatically translate
        $decodedResponse = json_decode($apiAnswer);dump($decodedResponse->weather);
        if(isset($decodedResponse->weather[0]->id)){ dump('BANG');
            $decodedResponse->weather[0]->description = $this->translate($decodedResponse->weather[0]->id);
        }
        return $decodedResponse;
    }

    /**
     * Basic translator.  We just translate weather string
     *
     * @param $code : Weather code
     * @return string : translated weather
     * @throws \NotFoundHttpException : in case $code doesn't exists
     */
    private function translate($code){
        $codes = array(
            200 => 'Orage avec pluie légère',
            201 => 'Orage avec pluie',
            202 => 'Orage avec pluie intense',
            210 => 'Petit orage',
            211 => 'Orage',
            212 => 'Orage intense',
            221 => 'Orage extrême',
            230 => 'Orage avec bruine légère',
            231 => 'Orage avec bruine',
            232 => 'Orage avec bruine intense',
            300 => 'Bruine de faible intensité',
            301 => 'Bruine',
            302 => 'Bruine de forte intensité',
            310 => 'Pluie bruineuse de faible intensité',
            311 => 'Pluie bruineuse',
            312 => 'Pluie bruineuse de forte intensité',
            313 => 'Pluie forte avec bruine',
            314 => 'Pluie intense avec bruine',
            312 => 'Bruine intense',
            500 => 'Pluie légère',
            501 => 'Pluie modérée',
            502 => 'Pluie de forte intensité',
            503 => 'Pluie très intense',
            504 => 'Pluie extrême',
            511 => 'Pluie verglaçante',
            520 => 'Averse faible',
            521 => 'Averse',
            522 => 'Averse de forte intensité',
            531 => 'Averse extrême',
            600 => 'Neige légère',
            601 => 'Neige',
            602 => 'Neige intense',
            611 => 'Neige fondante',
            612 => 'Grésil',
            615 => 'Pluie légère mêlée de neige',
            616 => 'Pluie et neige',
            620 => 'Averse de neigeuse modérée',
            621 => 'Averse neigeuse',
            622 => 'Averse neigeuse intense',
            701 => 'Brouillard léger',
            711 => 'Brouillard',
            721 => 'Brume',
            731 => 'Tempête de sable',
            741 => 'Brouillard intense',
            751 => 'Sable',
            761 => 'Poussière',
            762 => 'Poussière volcanique',
            771 => 'Bourrasques intenses',
            781 => 'Tornade',
            800 => 'Ciel dégagé',
            801 => 'Quelques nuages',
            802 => 'Nuages épars',
            803 => 'Nuages brisés',
            804 => 'Nuages importants',
            900 => 'Tornade',
            901 => 'Tempête tropicale',
            902 => 'Ouragan',
            903 => 'Froid',
            904 => 'Chaud',
            905 => 'Venteux',
            906 => 'Grêle',
            951 => 'Calme',
            952 => 'Brise légère',
            953 => 'Brise douce',
            954 => 'Brise modérée',
            955 => 'Brise fraîche',
            956 => 'Brise intense',
            957 => 'Vent fort avec bourrasques',
            958 => 'Bourrasques',
            959 => 'Bourrasques sévères',
            960 => 'Tempête',
            961 => 'Tempête violente',
            962 => 'Ouragan');
        if(!array_key_exists($code, $codes))
            throw new \NotFoundHttpException('The weather you\'re looking for doesn\'t exists in our database');
        return $codes[$code];
    }
}