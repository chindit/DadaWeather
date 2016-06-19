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

class DadaCache{
    private $cache; //Cache directory

    /**
     * Basic constructor.  Just storing data into args.
     * @param $cache string cache directory
     */
    public function __construct($cache){
        $this->cache = $cache;
    }

    /**
     * Verify if cache exists
     * @param $url string URL data requested to API
     * @return bool Cache exists
     */
    public function hasCache($url){
        $filename = $this->createFilenameFromUrl($url);
        return (is_file($filename) && filemtime($filename) > (time()-3600));
    }

    /**
     * Read the cache file and return it's content
     * @param $url string URL data requested to API
     * @return mixed content of API query
     */
    public function getCache($url){
        $filename = $this->createFilenameFromUrl($url);
        $content = unserialize(file_get_contents($filename));
        return $content;
    }

    /**
     * Write the cache
     * @param $url string URL data requested to API
     * @param $data mixed Data returned by API
     * @return bool returns EVERYTIME true
     * @throws UnexpectedResponseException Exception in case of write-error
     */
    public function writeCache($url, $data){
        $filename = $this->createFilenameFromUrl($url);
        $result = file_put_contents($filename, serialize($data));
        if($result === false)
            throw new UnexpectedResponseException('Unable to write cache. Please review parameters.  Thanks');
        return true; //Useless… but keeps IDE happy
    }

    /**
     * Generate the cache filename from the URL.  Basically, we just remove all meta-char and replace them with '_'
     * @param $url string URL data requested to API
     * @return string filename
     */
    private function createFilenameFromUrl($url){
        $transfo = array('?' => '_', '/' => '_', '&' => '_', '=' => '_');
        $filename = strtr($url, $transfo);
        return $this->cache.(($this->cache[(strlen($this->cache)-1)] == '/') ? '' : '/').$filename.'.dada_cache';
    }
}