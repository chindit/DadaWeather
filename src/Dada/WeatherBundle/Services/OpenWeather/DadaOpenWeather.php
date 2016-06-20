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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Dada\WeatherBundle\Services\Cache\DadaCache;

class DadaOpenWeather{

    private $api;
    private $args;
    private $apiUrl = 'http://api.openweathermap.org/data/2.5/forecast'; //Replace «forecast» with «weather» to get only current weather
    private $cache; //DadaCache instance

    /**
     * DadaOpenWeather constructor. Basic constructor.  Args can be send by calling «setParam()» method
     * @param $api string API key for OpenWeather
     * @param $cache string Path to cache directory
     */
    public function __construct($api, DadaCache $cache){
        $this->api = $api;
        $this->cache = $cache;
    }

    /**
     * Get weather form coordinates (Longitude and Latitude)
     *
     * @param $latitude : float
     * @param $longitude : float
     * @return Response : Return a JSON response
     */
    public function getWeatherFromCoords($latitude, $longitude){
        $response = $this->getAnswerFromApi(array('lat' => $latitude, 'lon' => $longitude)); //MUST be 'lat' and 'lon' or it will fails
        $ajaxResponse = new JsonResponse();
        $ajaxResponse->setContent(json_encode($response)); //We need to re-encode as JSON because JS is expecting it
        return $ajaxResponse;
    }


    /**
     * Get weather for a city ID
     *
     * @param $id : should be valid, it'd work better ;)
     * @throws \InvalidArgumentException : in case ID is not numeric or < 0
     * @return JSON value
     */
    public function getWeatherFromId($id){
        if(!is_numeric($id) || $id < 0)
            throw new \InvalidArgumentException('Expecting positive integer value');
        return $this->getAnswerFromApi(array('id' => $id));
    }

    /**
     * Get weather from a city name
     * 
     * @param $name string name of the city
     * @return array API response
     * @throws \NotFoundHttpException in case $name is invalid
     */
    public function getWeatherFromName($name){
        if(strlen($name) <= 3)
            throw new \InvalidArgumentException('Expecting string longer than 3 char');
        return $this->getAnswerFromApi(array('q' => $name)); //MUST be 'q' or search won't work
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
     * @param $params array Parameters to be passed to API
     * @return bool|mixed
     * @throws UnexpectedResponseException
     * @throws \NotFoundHttpException
     */
    private function getAnswerFromApi($params){
        //Check for cache
        if($this->cache->hasCache($params))
            return $this->cache->getCache($params); //Use cache if exists

        //Generate valid URL with params
        $url = $this->apiUrl.'?';
        foreach($params as $key => $value){
            $url .= $key.'='.$value.'&';
        }
        //Removing last "&"
        $url = substr($url, 0, (strlen($url)-1));
        
        //Adding API params
        $url .= $this->args;
        
        //Adding API key
        $url .= '&appid='.$this->api;

        //Checking URL before contact
        if(filter_var($url, FILTER_VALIDATE_URL) === false)
            throw new \InvalidArgumentException('Unable to contact the API.  URL appears to be malformed');
        $apiAnswer = file_get_contents($url); //Hello API :)
        if(empty($apiAnswer))
            throw new \NotFoundHttpException('Ooops… It seems the query you did to the API didn\'t show any answer.  Sad day, eh?');

        //We automatically translate
        $decodedResponse = json_decode($apiAnswer);

        if((int)$decodedResponse->cod == 200){
            //Everything is OK
            for($i=0; $i<count($decodedResponse->list); $i++){
                //UCFirst… better for my eyes
                $decodedResponse->list[$i]->weather[0]->description = ucfirst(trim($decodedResponse->list[$i]->weather[0]->description));

                //If we are here, we NEED to create cache (logical, no?)
                $this->cache->writeCache($decodedResponse); //Creating cache is as simple as that

                return $decodedResponse;
            }
        }
        else if((int)$decodedResponse->cod > 200){
            return false; //Default response is just «false»
        }
        else{
            //404 NOT ALLOWED -> Return custom error
            throw new UnexpectedResponseException('Hum… it\'s embarrasing… Seems like we\'ve received a response from an other galaxy instead of the weather you requested :(');
        }

        //We CANNOT reach this point!  This return is just too keep IDE quiet.
        return false;
    }

}