<?php

/*
 * The MIT License
 *
 * Copyright 2016 Simon Mall.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Mallapp\GeobitsBundle\Entity;

use Mallapp\GeobitsBundle\Model\Geocoding\SimpleLocation;

use Doctrine\ORM\Mapping as ORM;

/**
 * Geobit
 *
 * @ORM\MappedSuperclass
 */
class GeobitPlus extends Geobit
{

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255, nullable=true)
     */    
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="countryCode", type="string", length=128, nullable=true)
     */    
    private $countryCode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="administrativeArea", type="string", length=255, nullable=true)
     */
    private $administrativeArea;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;
    
    /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string", length=64, nullable=true)
     */
    private $postalCode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=128, nullable=true)
     */
    private $route;
    
    /**
     * @var string
     *
     * @ORM\Column(name="formattedAddress", type="string", length=1024, nullable=true)
     */
    private $formattedAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=1024, nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=64, nullable=true)
     */
    private $type;


    public static function createFromSimpleLocation(SimpleLocation $loc) {
        
        $geobit = new GeobitPlus();
        
        $now = new \DateTime;
        
        $geobit->setAdministrativeArea($loc->administrativeArea)
                ->setCity($loc->city)
                ->setCountryCode($loc->countryCode)
                ->setFormattedAddress($loc->formattedAddress)
                ->setNickname($loc->nickname)
                ->setPostalCode($loc->postalCode)
                ->setRoute($loc->route)
                ->setActive(true)
                ->setChangedAt($now)
                ->setGeneratedAt($now)
                ->setLatitude($loc->latitude)
                ->setLongitude($loc->longitude);
        
        return $geobit;
        
    }
    


    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return GeobitPlus
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        
        $this->touch();

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return GeobitPlus
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        $this->touch();
        
        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set administrativeArea
     *
     * @param string $administrativeArea
     *
     * @return GeobitPlus
     */
    public function setAdministrativeArea($administrativeArea)
    {
        $this->administrativeArea = $administrativeArea;

        $this->touch();
        
        return $this;
    }

    /**
     * Get administrativeArea
     *
     * @return string
     */
    public function getAdministrativeArea()
    {
        return $this->administrativeArea;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return GeobitPlus
     */
    public function setCity($city)
    {
        $this->city = $city;

        $this->touch();
        
        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return GeobitPlus
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        $this->touch();
        
        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return GeobitPlus
     */
    public function setRoute($route)
    {
        $this->route = $route;
        
        $this->touch();

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set formattedAddress
     *
     * @param string $formattedAddress
     *
     * @return GeobitPlus
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;

        $this->touch();
        
        return $this;
    }

    /**
     * Get formattedAddress
     *
     * @return string
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return GeobitPlus
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        $this->touch();
        
        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return GeobitPlus
     */
    public function setType($type)
    {
        $this->type = $type;

        $this->touch();
        
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
