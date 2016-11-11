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
 * The GeobitSyncGrid class expands an arbitrary CoordinateBox to the nearest
 * Grid Points, and allows to iterate through the Grid Elements.
 *
 * @author Simon Mall
 */
class GeobitSyncGrid implements \IteratorAggregate {
    
    private $expandedBox;
    
    public function __construct(CoordinateBox $coordinateBox) {
        
        // Keep an expanded version of the box.
        
        $this->expandedBox = new CoordinateBox(
                self::getLatNorthFromLatitude($coordinateBox->getNorthWest()->getLatitude()),
                self::getLonWestFromLongitude($coordinateBox->getNorthWest()->getLongitude()),
                self::getLatSouthFromLatitude($coordinateBox->getSouthEast()->getLatitude()),
                self::getLonEastFromLongitude($coordinateBox->getSouthEast()->getLongitude())
                );
        
    }
    
    public function getExpandedBox() {
        return $this->expandedBox;
    }

    public function getIterator() {
        
        return new GeobitSyncGridIterator($this->expandedBox);
        
    }

    
    /*
     * Static function definitions for conversion between Lat/Long and x/y
     */
    
    public static function getXFromLongitude($longitude) {
        
        // Mapping [-180.0 ... 180.0] to [-180...179]
        $x = intval(floor($longitude));
        
        // Map the positive 180 degree to the syncGridElement [179.0 ... 180.0]
        if ($x == 180) {
            $x = 179;
        }
        
        return $x;
        
    }
    
    public static function getYFromLatitude($latitude) {
        
        // Mapping [-90.0 ... 90.0] to [-90...89]
        $y = intval(floor($latitude));
        
        // Map the positive 90 degree to the syncGridElement [89.0 ... 90.0]
        if ($y == 90) {
            $y = 89;
        }
        
        return $y;
        
    }
    
    private static function getLonWestFromLongitude($longitude) {
        
        return floor($longitude);
        
    }
    
    private static function getLatSouthFromLatitude($latitude) {
        
        return floor($latitude);

    }
    
    private static function getLonEastFromLongitude($longitude) {
        
        return ceil($longitude);

    }
    
    private static function getLatNorthFromLatitude($latitude) {
        
        return ceil($latitude);

    }
    
    public static function getLatSpacing() {
        
        return 1.0;
        
    }
    
    public static function getLongSpacing() {
        
        return 1.0;
        
    }



}
