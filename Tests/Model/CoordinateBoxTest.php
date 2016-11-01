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

use Mallapp\GeobitsBundle\Model\CoordinateBox;
use Mallapp\GeobitsBundle\Model\Coordinate;



/**
 * Description of CoordinateBoxTest
 *
 * @author Simon Mall
 */
class CoordinateBoxTest extends \PHPUnit_Framework_TestCase {

    public function testObjectCreation() {

        $box = new CoordinateBox(30.0, -50.0, -30.0, 50.0);
        
        $this->assertEquals($box->getNorthWest()->getLatitude(), 30.0);
        $this->assertEquals($box->getNorthWest()->getLongitude(), -50.0);
        $this->assertEquals($box->getSouthEast()->getLatitude(), -30.0);
        $this->assertEquals($box->getSouthEast()->getLongitude(), 50.0);
        
        $box2 = new CoordinateBox(3540.0, -1900.0, -32403.0, 1494);
        
        $this->assertEquals($box2->getNorthWest()->getLongitude(), -100.0);
        $this->assertEquals($box2->getNorthWest()->getLatitude(), 90.0);
        $this->assertEquals($box2->getSouthEast()->getLongitude(), 54.0);
        $this->assertEquals($box2->getSouthEast()->getLatitude(), -90.0);
        
        $box3 = new CoordinateBox(-30.0, 50.0, 30.0, -50.0);
        
        $this->assertEquals($box3->getNorthWest()->getLongitude(), 50.0);
        $this->assertEquals($box3->getNorthWest()->getLatitude(), 30.0);
        $this->assertEquals($box3->getSouthEast()->getLongitude(), -50.0);
        $this->assertEquals($box3->getSouthEast()->getLatitude(), -30.0);
        $this->assertTrue($box3->getFlipAroundLong());
                
        
    }
    
    public function testContains() {
        
        $pointArray = $this->createTestPoints();
        
        // Case 1: standard
        $box1 = new CoordinateBox(30.0, -50.0, -30.0, 50.0);
        
        $this->assertTrue($box1->contains($pointArray[5]));
        
        $this->assertFalse($box1->contains($pointArray[1]));
        $this->assertFalse($box1->contains($pointArray[2]));
        $this->assertFalse($box1->contains($pointArray[3]));
        $this->assertFalse($box1->contains($pointArray[4]));
        $this->assertFalse($box1->contains($pointArray[6]));
        $this->assertFalse($box1->contains($pointArray[7]));
        $this->assertFalse($box1->contains($pointArray[8]));
        $this->assertFalse($box1->contains($pointArray[9]));
        
        
        // Case 2: flip around longitude
        $box2 = new CoordinateBox(30.0, 50.0, -30.0, -50.0);

        $this->assertTrue($box2->contains($pointArray[4]));
        $this->assertTrue($box2->contains($pointArray[6]));
        
        $this->assertFalse($box2->contains($pointArray[1]));
        $this->assertFalse($box2->contains($pointArray[2]));
        $this->assertFalse($box2->contains($pointArray[3]));
        $this->assertFalse($box2->contains($pointArray[5]));
        $this->assertFalse($box2->contains($pointArray[7]));
        $this->assertFalse($box2->contains($pointArray[8]));
        $this->assertFalse($box2->contains($pointArray[9]));
        
        
    }
    
    
    private function createTestPoints() {
        
        return array(
            1 => new Coordinate(40.0, -100.0),
            2 => new Coordinate(40.0, 10.0),
            3 => new Coordinate(40.0, 60.0),
            4 => new Coordinate(0.0, -100.0),
            5 => new Coordinate(0.0, 10.0),
            6 => new Coordinate(0.0, 60.0),
            7 => new Coordinate(-80.0, -100.0),
            8 => new Coordinate(-80.0, 10.0),
            9 => new Coordinate(-80.0, 60.0)
        );
        
    }

}
