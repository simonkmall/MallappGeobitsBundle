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

use Mallapp\GeobitsBundle\Model\CoordinateBox;

/**
 * Description of GeobitSyncGridElement
 *
 * @author Simon Mall
 */
class GeobitSyncGridElement {
    
    private $coordinateBox;
    
    private $x;
    
    private $y;
    
    private $uniqueId;
    
    /**
     * Create a SyncGrid Element.
     * 
     * @param CoordinateBox $coordinateBox
     * @param Integer $x
     * @param Integer $y
     */
    public function __construct($coordinateBox, $x, $y) {
        
        $this->coordinateBox = $coordinateBox;
        
        $this->x = $x;
        
        $this->y = $y;
        
        $this->uniqueId = $x.",".$y;
        
    }
    
    public function getCoordinateBox() {
        return $this->coordinateBox;
    }

    public function getUniqueId() {
        return $this->uniqueId;
    }

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }



    
}
