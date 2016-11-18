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
 *  A circle with a center coordinate (latitude, longitude) and a radius in m.
 *
 * @author Simon Mall
 */
class CoordinateCircle {
    
    private $center;
    
    private $radius;
    
    /**
     * 
     * @param type $latitude
     * @param type $longitude
     * @param type $radius Radius in m
     */
    public function __construct($latitude, $longitude, $radius) {
        
        $this->center = new Coordinate($latitude, $longitude);
        
        $this->radius = $this->sanitizeRadius($radius);
        
    }
    
    public function getCenter() {
        return $this->center;
    }

    public function getRadius() {
        return $this->radius;
    }

    public function containsCoordinate(Coordinate $coordinate) {
        
        $distance = $this->center->distanceToCoordinate($coordinate);
        
        if ($distance <= $this->radius) {
            
            return true;
            
        }
        
        return false;
        
    }   
    
    public function getBoundingBox() {
        
        return $this->center->boundingBox($this->radius);
        
    }
    
    private function sanitizeRadius($radius) {
        
        if (!is_numeric($radius)) {
            
            return 1.0;
            
        }
        
        if ($radius <= 0) {
            
            return 1.0;
            
        }
        
        return $radius;

    }

    
}
