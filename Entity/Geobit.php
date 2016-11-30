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
class Geobit
{

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="generatedAt", type="datetime")
     */
    private $generatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="changedAt", type="datetime")
     */
    private $changedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;


    public static function createFromSimpleLocation(SimpleLocation $loc) {
        
        $geobit = new Geobit();
        
        $now = new \DateTime;
        
        $geobit->setActive(true)
                ->setChangedAt($now)
                ->setGeneratedAt($now)
                ->setLatitude($loc->latitude)
                ->setLongitude($loc->longitude);
        
        return $geobit;
        
    }
    
    public function touch() {
        
        $this->setChangedAt(new \DateTime());
        
    }


    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Geobit
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        
        $this->touch();

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
     * @return Geobit
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        $this->touch();
                
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
     * Set generatedAt
     *
     * @param \DateTime $generatedAt
     *
     * @return Geobit
     */
    public function setGeneratedAt($generatedAt)
    {
        $this->generatedAt = $generatedAt;

        return $this;
    }

    /**
     * Get generatedAt
     *
     * @return \DateTime
     */
    public function getGeneratedAt()
    {
        return $this->generatedAt;
    }

    /**
     * Set changedAt
     *
     * @param \DateTime $changedAt
     *
     * @return Geobit
     */
    public function setChangedAt($changedAt)
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    /**
     * Get changedAt
     *
     * @return \DateTime
     */
    public function getChangedAt()
    {
        return $this->changedAt;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Geobit
     */
    public function setActive($active)
    {
        $this->active = $active;

        $this->touch();
        
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
