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

namespace Mallapp\GeobitsBundle\Tests\Entity;

use Mallapp\GeobitsBundle\Entity\GeobitPlus;

/**
 * Description of GeobitPlusTest
 *
 * @author Simon Mall
 */
class GeobitPlusTest extends \PHPUnit_Framework_TestCase {


    /*
     * Smoke test to verify that we did not override the setter methods
     * and thereby accidentally remove the touch() function.
     * The touch() function is necessary to set the changedAt field,
     * which in turn is necessary to correctly asses which geobits
     * are cached and which aren't.
     */
    public function testCreationAndTouchFunction() {
        
        $dateString = "2005-08-15T15:00:00+0000";
        
        $gb = new GeobitPlus();
        
        // Set the time
        $gb->setChangedAt(\DateTime::createFromFormat(\DateTime::ISO8601, $dateString));
        
        // Check if set correctly
        $this->assertEquals($dateString, $gb->getChangedAt()->format(\DateTime::ISO8601));
        
        // Change any field
        $gb->setCity("Testcity");
        
        // Check if updated, i.e. not to the previous value anymore
        $this->assertNotEquals($dateString, $gb->getChangedAt()->format(\DateTime::ISO8601));

    }

}
