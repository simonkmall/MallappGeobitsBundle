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


namespace Mallapp\GeobitsBundle\Tests\Model\Geocoding;

use Mallapp\GeobitsBundle\Model\Geocoding\ReverseGeocodingApi;
use Mallapp\GeobitsBundle\Entity\GeobitEntity;

/**
 * Description of ReverseGeocodingApiTest
 *
 * @author Simon Mall
 */
class ReverseGeocodingApiTest extends \PHPUnit_Framework_TestCase {

    public function testObject() {
        
        
        $loc = ReverseGeocodingApi::getLocationFromCoordinate(47.385777, 8.500454);
        
        $this->assertEquals($loc->nickname, "Baslerstrasse Zürich - Kreis 9 (CH)");
        $this->assertEquals($loc->route, "Baslerstrasse");
        $this->assertEquals($loc->postalCode, "8048");
        $this->assertEquals($loc->city, "Zürich");
        $this->assertEquals($loc->countryCode, "CH");
        $this->assertEquals($loc->administrativeArea, "Zürich");
        $this->assertEquals($loc->latitude, 47.385777 );
        $this->assertEquals($loc->longitude, 8.500454 );
        $this->assertEquals($loc->formattedAddress, "Baslerstrasse 44, 8048 Zürich, Switzerland");
        

    }
    
    public function testGeobitcreation() {
        
        $loc = ReverseGeocodingApi::getLocationFromCoordinate(47.385777, 8.500454);
        
        $geobit = GeobitEntity::createFromSimpleLocation($loc);
        
        $this->assertEquals($geobit->getNickname(), "Baslerstrasse Zürich - Kreis 9 (CH)");
        $this->assertEquals($geobit->getRoute(), "Baslerstrasse");
        $this->assertEquals($geobit->getPostalCode(), "8048");
        $this->assertEquals($geobit->getCity(), "Zürich");
        $this->assertEquals($geobit->getCountryCode(), "CH");
        $this->assertEquals($geobit->getAdministrativeArea(), "Zürich");
        $this->assertEquals($geobit->getLatitude(), 47.385777 );
        $this->assertEquals($geobit->getLongitude(), 8.500454 );
        $this->assertEquals($geobit->getFormattedAddress(), "Baslerstrasse 44, 8048 Zürich, Switzerland");
        
        
    }

}
