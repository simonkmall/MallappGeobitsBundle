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

namespace Mallapp\GeobitsBundle\Model;

/**
 * A bounding box of (latitude, longitude) coordinates.
 * Make sure upon creation, that the northWest and southEast points 
 * are correctly assigned.
 *
 * @author Simon Mall
 */
class CoordinateBox {
    
    private $northWest;
    
    private $southEast;
    
    private $flipAroundLong;
    
    private static $minLat = -90.0;
    private static $maxLat = 90.0;
    private static $minLong = -180.0;
    private static $maxLong = 180.0;
    private static $fullLong = 360.0;
    
    
    public function __construct($northWestLatitude, $northWestLongitude, $southEastLatitude, $southEastLongitude) {
        
        if ($northWestLatitude > self::$maxLat) { $northWestLatitude = self::$maxLat; }
        if ($southEastLatitude > self::$maxLat) { $southEastLatitude = self::$maxLat; }
        if ($northWestLatitude < self::$minLat) { $northWestLatitude = self::$minLat; }
        if ($southEastLatitude < self::$minLat) { $southEastLatitude = self::$minLat; }
        
        while ($northWestLongitude > self::$maxLong) { $northWestLongitude -= self::$fullLong; }
        while ($northWestLongitude < self::$minLong) { $northWestLongitude += self::$fullLong; }
        while ($southEastLongitude > self::$maxLong) { $southEastLongitude -= self::$fullLong; }
        while ($southEastLongitude < self::$minLong) { $southEastLongitude += self::$fullLong; }
        
        $this->northWest = new Coordinate($northWestLatitude, $northWestLongitude);
        $this->southEast = new Coordinate($southEastLatitude, $southEastLongitude);
        
        $this->flipAroundLong = false;
        
        if ($this->getNorthWest()->getLongitude() > 
                $this->getSouthEast()->getLongitude()) {
            
            $this->flipAroundLong = true;
            
        }  
        
        if ($this->getNorthWest()->getLatitude() <
                $this->getSouthEast()->getLatitude()) {
            
            // Correct the incorrect latitudes. Because this makes no sense at 
            // all (and should really never be generated that way).
            
            $temp = $this->getNorthWest()->getLatitude();
            $this->getNorthWest()->setLatitude($this->getSouthEast()->getLatitude());
            $this->getSouthEast()->setLatitude($temp);
            
        }
        
    }
    
    public function getFlipAroundLong() {
        return $this->flipAroundLong;
    }

        
    /**
     * 
     * @return Coordinate NorthWest
     */
    public function getNorthWest() {
        return $this->northWest;
    }

    /**
     * 
     * @return Coordinate SouthEast
     */
    public function getSouthEast() {
        return $this->southEast;
    }
    
    
    public function contains(Coordinate $coordinate) {
        

        if ($this->flipAroundLong) {
            
            return ($coordinate->getLongitude() >= $this->getNorthWest()->getLongitude() ||
                    $coordinate->getLongitude() <= $this->getSouthEast()->getLongitude()) &&
                    ($coordinate->getLatitude() >= $this->getSouthEast()->getLatitude() && 
                    $coordinate->getLatitude() <= $this->getNorthWest()->getLatitude());
            
        }
        else {
            
            return ($coordinate->getLongitude() >= $this->getNorthWest()->getLongitude() &&
                    $coordinate->getLongitude() <= $this->getSouthEast()->getLongitude()) &&
                    ($coordinate->getLatitude() >= $this->getSouthEast()->getLatitude() && 
                    $coordinate->getLatitude() <= $this->getNorthWest()->getLatitude());            
            
        }
        
        return false;
        
        
    }



    
    
}
