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
 * First generated : 06/19/2016 at 12:30
 */

namespace Dada\WeatherBundle\Services\Cache;

use Dada\WeatherBundle\Services\OpenWeather\InvalidRequestException;
use Dada\WeatherBundle\Entity\CachedWeather;

class DadaCache{
    private $repository; //Doctrine repo
    private $doctrine; //Doctrine _em

    /**
     * Basic constructor.  Just storing data into args.
     * @param $cache string cache directory
     */
    public function __construct($doctrine){
        $this->repository = $doctrine->getRepository('DadaWeatherBundle:CachedWeather'); //Calling directly repo to earn some time
        $this->doctrine = $doctrine;
    }

    /**
     * Verify if cache exists
     * @param $data array parameters send to API
     * @return bool Cache exists
     * @throws InvalidRequestException In case request made to API could not be determined
     */
    public function hasCache($data){
        //3 options: Location, ID or town name.
        if(array_key_exists('id', $data)){ //We've got an ID
            return (!empty($this->repository->findByTownId($data['id'])));
        }
        else if(array_key_exists('q', $data)){ //Search string. WARNING: will not work very well
            return (!empty($this->repository->findByTownName($data['q'])));
        }
        else if(array_key_exists('lat', $data)){
            //In case of coords, we accept 1' diff (~1km -> ~1.9Km)
            return (!empty($this->repository->findByCoords($data['lat'], $data['lon'])));
        }
        else{
            //CANNOT exists
            throw new InvalidRequestException('The request you\'ve made to the API is not valid');
        }
    }

    /**
     * Read the cache file and return it's content
     * @param $url string URL data requested to API
     * @return mixed content of API query
     */
    public function getCache($data){
        //Use this for debug
        //dump('CACHED');
        
        //3 options: Location, ID or town name.
        if(array_key_exists('id', $data)){ //We've got an ID
            return $this->repository->findByTownId($data['id'], true)->getData();
        }
        else if(array_key_exists('q', $data)){ //Search string. WARNING: will not work very well
            return $this->repository->findByTownName($data['q'], true)->getData();
        }
        else if(array_key_exists('lat', $data)){
            //In case of coords, we accept 1' diff (~1km -> ~1.9Km)
            return $this->repository->findByCoords($data['lat'], $data['lon'], true)->getData();
        }
        else{
            //CANNOT exists
            throw new InvalidRequestException('The request you\'ve made to the API is not valid');
        }
    }

    /**
     * Write the cache
     * @param $data array Data returned by API
     * @return void
     */
    public function writeCache($data){
        $toCache = new CachedWeather();
        $toCache->setTownId($data->city->id);
        $toCache->setTownName($data->city->name);
        $toCache->setLatitude($data->city->coord->lat);
        $toCache->setLongitude($data->city->coord->lon);
        $toCache->setData($data);
        
        //When here, we clean cache.  We could have done it on pre-persist but it leaves the townName's bug alive :(
        $this->repository->cleanCache();
        $this->doctrine->flush(); //Flush before insert for safety

        //Workaround for INSERT IGNORE
        try{
            $this->doctrine->persist($toCache);
            $this->doctrine->flush();
        }
        catch(\Exception $e){
            if($e->getErrorCode() !== 1062){ //1062 is the key for duplicate content
                throw $e;
            }
        }

       return;
    }
    
}