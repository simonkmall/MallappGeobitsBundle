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

namespace Mallapp\GeobitsBundle\Tests\Model;

use Mallapp\GeobitsBundle\Model\GeobitSyncGridIterator;
use Mallapp\GeobitsBundle\Model\CoordinateBox;
use Mallapp\GeobitsBundle\Model\GeobitSyncGrid;

/**
 * Description of GeobitSyncGridIteratorTest
 *
 * @author Simon Mall
 */
class GeobitSyncGridIteratorTest extends \PHPUnit_Framework_TestCase {

    public function testIteratorWithNonFlipping() {
        
        // First test with non-flipping coordinate box
        // Should iterate over a 2x2 box, i.e. 4 unit coordinate boxes
        
        $box = new CoordinateBox(34.0, 128.0, 32.0, 130.0);
        
        $synchGrid = new GeobitSyncGrid($box);
        
        $iterator = new GeobitSyncGridIterator($synchGrid->getExpandedBox());
        
        // Iterate and fill an array for later assertion
        $iteratorElements = Array();
        
        foreach ($iterator as $iteratorElement) {
            
            $iteratorElements[] = $iteratorElement;
            
        }
        
        $this->assertEquals(4, count($iteratorElements));
        
        $this->assertEquals("128,32", $iteratorElements[0]->getUniqueId());
        $this->assertEquals("129,32", $iteratorElements[1]->getUniqueId());
        $this->assertEquals("128,33", $iteratorElements[2]->getUniqueId());
        $this->assertEquals("129,33", $iteratorElements[3]->getUniqueId());

        $this->assertEquals(128.0, $iteratorElements[2]->getCoordinateBox()->getNorthWest()->getLongitude());
        $this->assertEquals(34.0, $iteratorElements[2]->getCoordinateBox()->getNorthWest()->getLatitude());
        $this->assertEquals(129.0, $iteratorElements[2]->getCoordinateBox()->getSouthEast()->getLongitude());
        $this->assertEquals(33.0, $iteratorElements[2]->getCoordinateBox()->getSouthEast()->getLatitude());        
        

    }
    
    public function testIteratorWithFlipping() {
        
        // First test with flipping coordinate box
        // Should iterate over a 4x2 box, i.e. 8 unit coordinate boxes
        
        $box = new CoordinateBox(34.0, 178.0, 32.0, -178.0);
        
        $synchGrid = new GeobitSyncGrid($box);
        
        $iterator = new GeobitSyncGridIterator($synchGrid->getExpandedBox());
        
        // Iterate and fill an array for later assertion
        $iteratorElements = Array();
        
        foreach ($iterator as $iteratorElement) {
            
            $iteratorElements[] = $iteratorElement;
            
        }
        
        $this->assertEquals(8, count($iteratorElements));
        
        $this->assertEquals("178,32", $iteratorElements[0]->getUniqueId());
        $this->assertEquals("179,32", $iteratorElements[1]->getUniqueId());
        $this->assertEquals("-180,32", $iteratorElements[2]->getUniqueId());
        $this->assertEquals("-179,32", $iteratorElements[3]->getUniqueId());
        $this->assertEquals("178,33", $iteratorElements[4]->getUniqueId());
        $this->assertEquals("179,33", $iteratorElements[5]->getUniqueId());
        $this->assertEquals("-180,33", $iteratorElements[6]->getUniqueId());
        $this->assertEquals("-179,33", $iteratorElements[7]->getUniqueId());
        
        
        $this->assertEquals(179.0, $iteratorElements[1]->getCoordinateBox()->getNorthWest()->getLongitude());
        $this->assertEquals(33.0, $iteratorElements[1]->getCoordinateBox()->getNorthWest()->getLatitude());
        $this->assertEquals(180.0, $iteratorElements[1]->getCoordinateBox()->getSouthEast()->getLongitude());
        $this->assertEquals(32.0, $iteratorElements[1]->getCoordinateBox()->getSouthEast()->getLatitude());        
        
        $this->assertEquals(-180.0, $iteratorElements[2]->getCoordinateBox()->getNorthWest()->getLongitude());
        $this->assertEquals(33.0, $iteratorElements[2]->getCoordinateBox()->getNorthWest()->getLatitude());
        $this->assertEquals(-179.0, $iteratorElements[2]->getCoordinateBox()->getSouthEast()->getLongitude());
        $this->assertEquals(32.0, $iteratorElements[2]->getCoordinateBox()->getSouthEast()->getLatitude());        
        

    }

}
