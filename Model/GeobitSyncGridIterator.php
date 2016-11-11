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
 * Description of GeobitSyncGridIterator
 *
 * @author Simon Mall
 */
class GeobitSyncGridIterator implements \Iterator {
    
    private $minLong;
    private $maxLong; 
    private $minLat;
    private $maxLat;
    
    private $currentLong;
    private $currentLat;
    
    private $valid;
    private $flipped;
    
    public function __construct(CoordinateBox $expandedBox) {
        
         // Assuming non-flipped box for now (correction otherwise is done in the for loop)
        
        $this->minLong = $expandedBox->getNorthWest()->getLongitude();
        $this->maxLong = $expandedBox->getSouthEast()->getLongitude();
        $this->minLat = $expandedBox->getSouthEast()->getLatitude();
        $this->maxLat = $expandedBox->getNorthWest()->getLatitude();
        
        $this->flipped = false;
        
    }

    
    public function current() {
        
        return new GeobitSyncGridElement(
                    new CoordinateBox($this->currentLat, $this->currentLong, $this->currentLat + GeobitSyncGrid::getLatSpacing(), $this->currentLong + GeobitSyncGrid::getLongSpacing()),
                    GeobitSyncGrid::getXFromLongitude($this->currentLong),
                    GeobitSyncGrid::getYFromLatitude($this->currentLat)
                    );
        
    }

    public function key() {
        
        return intval($this->currentLat * $this->maxLong + $this->currentLong);
        
    }

    public function next() {
        
        $this->currentLong += 1.0;
        
        if (($this->currentLong >= $this->maxLong) && (($this->minLong < $this->maxLong) || ($this->flipped == true))){
            
            // reached the next "line"
            $this->currentLong = $this->minLong;
            $this->currentLat += 1.0;
            $this->flipped = false;
            
            if ($this->currentLat >= $this->maxLat) {
                
                // reached the end
                $this->valid = false;
                
            }
            
        }
        
        if ($this->currentLong >= Coordinate::$maxLong) {
            
            // We reached a longitude of +180 degrees. We need to wrap around to -180 degrees.
            $this->currentLong = Coordinate::$minLong;
            
            $this->flipped = true;
            
        }
        
    }

    public function rewind() {
        
        // Reset indices to initial position
        
        $this->currentLat = $this->minLat;
        $this->currentLong = $this->minLong;
        
        $this->valid = true;
        
        $this->flipped = false;
        
        
    }

    public function valid() {
        
        return $this->valid;
        
    }
        

}

