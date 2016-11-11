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

use Mallapp\GeobitsBundle\Model\GeobitSyncGrid;
use Mallapp\GeobitsBundle\Model\CoordinateBox;


/**
 * Description of GeobitSynchGridTest
 *
 * @author Simon Mall
 */
class GeobitSynchGridTest extends \PHPUnit_Framework_TestCase {

    public function testBoxExpansion() {
        
        $box = new CoordinateBox(-30.23, -30.98, -31.2, -28.3);
        
        $synchGrid = new GeobitSyncGrid($box);
        
        $expBox = $synchGrid->getExpandedBox();
        
        $this->assertEquals($expBox->getNorthWest()->getLatitude(), -30.0);
        $this->assertEquals($expBox->getNorthWest()->getLongitude(), -31.0);
        $this->assertEquals($expBox->getSouthEast()->getLatitude(), -32.0);
        $this->assertEquals($expBox->getSouthEast()->getLongitude(), -28.0);
        
        
        $box2 = new CoordinateBox(90.00, 150.2, -44.2, -150.9);
        
        $synchGrid2 = new GeobitSyncGrid($box2);
        
        $expBox2 = $synchGrid2->getExpandedBox();
        
        $this->assertEquals($expBox2->getNorthWest()->getLatitude(), 90.0);
        $this->assertEquals($expBox2->getNorthWest()->getLongitude(), 150.0);
        $this->assertEquals($expBox2->getSouthEast()->getLatitude(), -45.0);
        $this->assertEquals($expBox2->getSouthEast()->getLongitude(), -150.0);
        
    }

}
