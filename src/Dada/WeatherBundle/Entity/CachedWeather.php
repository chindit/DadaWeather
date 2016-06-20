<?php

namespace Dada\WeatherBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CachedWeather
 *
 * @ORM\Table(name="cached_weather")
 * @ORM\Entity(repositoryClass="Dada\WeatherBundle\Repository\CachedWeatherRepository")
 */
class CachedWeather
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="townName", type="string", length=255, unique=true)
     */
    private $townName;

    /**
     * @var int
     *
     * @ORM\Column(name="townId", type="integer", unique=true)
     */
    private $townId;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", unique=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", unique=true)
     */
    private $longitude;

    /**
     * @var array
     * 
     * @ORM\Column(name="data", type="array")
     */
    private $data;

    /**
     * @var datetime
     * 
     * @ORM\Column(name="registered", type="datetime")
     */
    private $registered;


    /**
     * Basic constructor (and yes, I know there is an extension «timestampable», ok?)
     */
    public function __construct(){
        $this->registered = new \DateTime();
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set townName
     *
     * @param string $townName
     *
     * @return CachedWeather
     */
    public function setTownName($townName)
    {
        $this->townName = $townName;

        return $this;
    }

    /**
     * Get townName
     *
     * @return string
     */
    public function getTownName()
    {
        return $this->townName;
    }

    /**
     * Set townId
     *
     * @param integer $townId
     *
     * @return CachedWeather
     */
    public function setTownId($townId)
    {
        $this->townId = $townId;

        return $this;
    }

    /**
     * Get townId
     *
     * @return int
     */
    public function getTownId()
    {
        return $this->townId;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return CachedWeather
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return CachedWeather
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set registered
     *
     * @param \DateTime $registered
     *
     * @return CachedWeather
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return CachedWeather
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
