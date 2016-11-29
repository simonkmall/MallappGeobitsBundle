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

/**
 * GeobitEntity
 *
 */
class GeobitEntity extends Geobit
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var string
     *
     */    
    private $nickname;

    /**
     * @var string
     *
     */    
    private $countryCode;
    
    /**
     * @var string
     *
     */
    private $administrativeArea;
    
    /**
     * @var string
     *
     */
    private $city;
    
    /**
     * @var string
     *
     */
    private $postalCode;
    
    /**
     * @var string
     *
     */
    private $route;
    
    /**
     * @var string
     *
     */
    private $formattedAddress;

    /**
     * @var string
     *
     */
    private $comment;

    /**
     * @var string
     *
     */
    private $type;


    public static function createFromSimpleLocation(SimpleLocation $loc) {
        
        $geobit = new GeobitEntity();
        
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Geobit
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        
        $this->changedAt = new \DateTime();

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
     * @return GeobitEntity
     */
    public function setType($type)
    {
        $this->type = $type;

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

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return GeobitEntity
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

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
     * @return GeobitEntity
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

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
     * @return GeobitEntity
     */
    public function setAdministrativeArea($administrativeArea)
    {
        $this->administrativeArea = $administrativeArea;

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
     * @return GeobitEntity
     */
    public function setCity($city)
    {
        $this->city = $city;

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
     * @return GeobitEntity
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

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
     * @return GeobitEntity
     */
    public function setRoute($route)
    {
        $this->route = $route;

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
     * @return GeobitEntity
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;

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
}
