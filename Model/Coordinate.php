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
 * Simple Coordinate object (latitude, longitude)
 *
 * @author Simon Mall
 */
class Coordinate {
    
    private static $earthRadiusInMeters = 6371000.0;
    private static $minLatRad = -M_PI / 2.0;
    private static $maxLatRad = M_PI / 2.0;
    private static $minLongRad = -M_PI;
    private static $maxLongRad = M_PI;
    
    
    private $latitude;
    
    private $longitude;
    
    public function __construct($latitude, $longitude) {
        
        $this->setLatitude($latitude);
        
        $this->setLongitude($longitude);
        
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function setLatitude($latitude) {
        
        if (!is_numeric($latitude)) {
        
            $this->latitude = 0.0;
            
            return false;
            
        }
        
        if ($latitude > 90.0) {
            
            $this->latitude = 90.0;
            
            return false;
            
        }
        
        if ($latitude < -90.0) {
            
            $this->latitude = -90.0;
            
            return false;
            
        }
        
        $this->latitude = $latitude;
        
        return true;
        
    }

    public function setLongitude($longitude) {
        
        if (!is_numeric($longitude)) {
        
            $this->longitude = 0.0;
            
            return false;
            
        }
        
        if ($longitude >= 180.0) {
            
            $this->longitude = 179.99;
            
            return false;
            
        }
        
        if ($longitude <= -180.0) {
            
            $this->longitude = -179.99;
            
            return false;
            
        }
        
        $this->longitude = $longitude;
        
        return true;
        
    }

    
    

    /**
     * Returns the distance in m between two coordinates
     * Uses the Haversine formula.
     * 
     * @param \Mallapp\GeobitsBundle\Model\Coordinate $coordinate
     * @return integer Distance in meters
     */
    public function distanceToCoordinate(Coordinate $coordinate) {
        
            $lat1rad = deg2rad($this->latitude);
            $lat2rad = deg2rad($coordinate->latitude);
            $lon1rad = deg2rad($this->longitude);
            $lon2rad = deg2rad($coordinate->longitude);

            $dlon = $lon2rad - $lon1rad;
            $dlat = $lat2rad - $lat1rad;
            $a = pow(sin($dlat/2.0),2) + cos($lat1rad) * cos($lat2rad) * pow(sin($dlon/2.0),2);
            $c = 2.0 * atan2( sqrt($a), sqrt(1.0-$a) );
            $d = self::$earthRadiusInMeters * $c ;

            return $d;

    }
    
    /**
     * Returns the bounding box containing all coordinates (and more) that
     * no farther away than distance.
     * Source: http://janmatuschek.de/LatitudeLongitudeBoundingCoordinates
     * 
     * @param float $distance Distance in [m]
     * @return CoordinateBox The bounding box
     */
    public function boundingBox($distance) {
        
        if ($distance < 0) {
            throw new Exception("Invalid Distance: ".$distance);
        }
        
        $lat1rad = deg2rad($this->latitude);
        $lon1rad = deg2rad($this->longitude);
        
        $angularDistance = $distance / self::$earthRadiusInMeters;
        
        $minLat = $lat1rad - $angularDistance;
        $maxLat = $lat1rad + $angularDistance;
        
        $minLon = 0.0;
        $maxLon = 0.0;
        
        if ($minLat > self::$minLatRad && $maxLat < self::$maxLatRad) {
            
            $deltaLon = asin(sin($angularDistance) / cos($lat1rad));
            $minLon = $lon1rad - $deltaLon;
            
            if ($minLon < self::$minLongRad) {
                $minLon += 2.0 * M_PI;
            }
            
            $maxLon = $lon1rad + $deltaLon;
            
            if ($maxLon > self::$maxLongRad) {
                $maxLon -= 2.0 * M_PI;
            }
            
        }
        else {
            
            $minLat = max($minLat, self::$minLatRad);
            $maxLat = min($maxLat, self::$maxLatRad);
            $minLon = self::$minLongRad;
            $maxLon = self::$maxLongRad;
            
        }
        
        return new CoordinateBox(
                rad2deg($minLat), 
                rad2deg($minLon), 
                rad2deg($maxLat), 
                rad2deg($maxLon)
                );
        

        
        
    }
    
 
}
