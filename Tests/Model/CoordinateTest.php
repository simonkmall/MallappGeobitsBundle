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

use Mallapp\GeobitsBundle\Model\Coordinate;

/**
 * Description of CoordinateTest
 *
 * @author Simon Mall
 */
class CoordinateTest extends \PHPUnit_Framework_TestCase {
    
    public function testCoordinate() {
        
        // Test sanitazing
        $coord = new Coordinate("TEST", "TEST");
        
        $this->assertEquals($coord->getLatitude(), 0.0);
        $this->assertEquals($coord->getLongitude(), 0.0);
        
        $coord1 = new Coordinate(-2000.0, -2000.0);
        
        $this->assertEquals($coord1->getLatitude(), -90.0);
        $this->assertEquals($coord1->getLongitude(), -179.99);
        
        $coord2 = new Coordinate(2000.0, 2000.0);
        
        $this->assertEquals($coord2->getLatitude(), 90.0);
        $this->assertEquals($coord2->getLongitude(), 179.99);       
        
        // Test correct
        $coord3 = new Coordinate(12.3, -45.0);
        
        $this->assertEquals($coord3->getLatitude(), 12.3);
        $this->assertEquals($coord3->getLongitude(), -45.0);   
        
    }
    
    public function testHaversineFormula() {
        
        $point1 = new Coordinate(47.379274, 8.512315);
        $point2 = new Coordinate(47.368778, 8.532750);
        
        $distance1 = $point1->distanceToCoordinate($point2);
        
        $this->assertEquals(round($distance1, -1), 1930.0, '');
        
        $point3 = new Coordinate(43.565468, 21.330713);
        
        $distance2 = $point1->distanceToCoordinate($point3);
        
        $this->assertEquals(round($distance2, -3), 1084000.0, '');
        
    }
    
    public function testBoundingBox() {
        
                
        // Test standard case
        $pointStd = new Coordinate(47.19408, 8.49157);
        $pointStdNear = new Coordinate(47.18125, 8.50573);
        $pointStdFar = new Coordinate(47.1844, 8.52581);
        
        $distanceStd = 1920.0;

        $bboxStd = $pointStd->boundingBox($distanceStd);
        
        $this->assertTrue($bboxStd->contains($pointStd));
        $this->assertTrue($bboxStd->contains($pointStdNear));
        $this->assertFalse($bboxStd->contains($pointStdFar));
        

        // Test wrap around 180° longitude
        $pointLong = new Coordinate(35.45169, -179.15401);
        $pointLongNear = new Coordinate(35.14012, -178.84643);
        $pointLongNear2 = new Coordinate(35.45214, 179.14383);
        $pointLongFar = new Coordinate(34.71678, 177.48413);
        $pointLongFar2 = new Coordinate(33.39934, -178.87939);
        
        $distanceLong = 159000.0;
        
        $bboxLong = $pointLong->boundingBox($distanceLong);
        
        $this->assertTrue($bboxLong->contains($pointLong));
        $this->assertTrue($bboxLong->contains($pointLongNear));
        $this->assertTrue($bboxLong->contains($pointLongNear2));
        $this->assertFalse($bboxLong->contains($pointLongFar));
        $this->assertFalse($bboxLong->contains($pointLongFar2));
        
        
    }
    
    
}
