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

use Mallapp\GeobitsBundle\Model\GeobitCollection;
use Mallapp\GeobitsBundle\Model\CoordinateCircle;
use Mallapp\GeobitsBundle\Model\CoordinateBox;
use Mallapp\GeobitsBundle\Model\Coordinate;

/**
 * Description of GeobitCollectionTest
 *
 * @author Simon Mall
 */
class GeobitCollectionTest extends \PHPUnit_Framework_TestCase {

    public function testBoundingBoxWithoutCache() {

        // Currently no unit tests available.
        
        $this->assertTrue(true);
        
    }
    
    private function createBoxPoints() {
    
        return array(
            "A" => new Coordinate(30.0, -50.0),
            "B" => new Coordinate(30.0, 50.0),
            "C" => new Coordinate(-30.0, -50.0),
            "D" => new Coordinate(-30.0, 50.0)
        );
        
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
