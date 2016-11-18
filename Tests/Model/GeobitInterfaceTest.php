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

use Mallapp\GeobitsBundle\Model\GeobitInterface;
use Mallapp\GeobitsBundle\Model\CoordinateBox;
use Mallapp\GeobitsBundle\Model\CoordinateCircle;



use Mallapp\GeobitsBundle\Entity\Geobit;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\Tools\SchemaTool;


/**
 * Description of GeobitInterfaceTest
 *
 * @author Simon Mall
 */
class GeobitInterfaceTest extends KernelTestCase {
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        // Make sure we are in the test environment
        if ('test' !== static::$kernel->getEnvironment()) {
            throw new \LogicException('Primer must be executed in the test environment');
        }
        
        // Run the schema update tool using our entity metadata
        $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->updateSchema($metadatas);

                
    }

    

    public function testPutFunctions() {

        $gb1 = new Geobit();
        $gb1->setLatitude(10.0);
        $gb1->setLongitude(30.0);
        $gb1->setActive(true);
        
        $gb2 = new Geobit();
        $gb2->setLatitude(20.0);
        $gb2->setLongitude(40.0);
        $gb2->setActive(true);
        
        
        $gInterface = GeobitInterface::create(
                $this->em->getRepository('MallappGeobitsBundle:Geobit'),
                $this->em->getRepository('MallappGeobitsBundle:UserRetrieval'),
                $this->em
                );
        
        $gInterface->put($gb1);
        $gInterface->put($gb2);
        $gInterface->flushDB();
        
        $geobits = $this->em->getRepository('MallappGeobitsBundle:Geobit')->findAll();
        
        $this->assertCount(2, $geobits);
        
        
        $gInterface->setActiveForGeobitArray($geobits, false);
        
        $gInterface->flushDB();
        
        $inactiveGeobits = $this->em->getRepository('MallappGeobitsBundle:Geobit')->findByActive(false);
        $activeGeobits = $this->em->getRepository('MallappGeobitsBundle:Geobit')->findByActive(true);
        
        $this->assertCount(2, $inactiveGeobits);
        $this->assertCount(0, $activeGeobits);
        
    }
    
    public function testRetrievalWithoutCache() {
        
        $gInterface = GeobitInterface::create(
                $this->em->getRepository('MallappGeobitsBundle:Geobit'),
                $this->em->getRepository('MallappGeobitsBundle:UserRetrieval'),
                $this->em
                );
                
        $gb1 = new Geobit();
        $gb1->setLatitude(47.15703);
        $gb1->setLongitude(8.25759);
        $gb1->setActive(true);
        
        $gb2 = new Geobit();
        $gb2->setLatitude(47.10658);
        $gb2->setLongitude(8.17451);
        $gb2->setActive(true);
        
        $gb3 = new Geobit();
        $gb3->setLatitude(47.09911);
        $gb3->setLongitude(8.25279);
        $gb3->setActive(true);
        
        $gb4 = new Geobit();
        $gb4->setLatitude(48.00111);
        $gb4->setLongitude(8.25279);
        $gb4->setActive(true);
        
        $gInterface->put($gb1);
        $gInterface->put($gb2);
        $gInterface->put($gb3);
        $gInterface->put($gb4);
        $gInterface->flushDB();

        $gInCircle = $gInterface->getAllGeobitsInCircle(new CoordinateCircle(47.15703, 8.25759, 7000));
        
        $this->assertCount(2, $gInCircle);
        
        $this->assertTrue(($gInCircle[0]->getLatitude() == 47.15703) || ($gInCircle[0]->getLatitude() == 47.09911));
        $this->assertTrue(($gInCircle[1]->getLatitude() == 47.15703) || ($gInCircle[1]->getLatitude() == 47.09911));
        
        // Note that this function extends the grid!
        $gInRect = $gInterface->getAllGeobitsInRect(new CoordinateBox(47.14361, 8.15649, 47.09256, 8.26292));
        
        $this->assertCount(3, $gInRect);

        
    }
    
    public function testRetrievalWithCache() {
        
        $userToken = "1234";
        $anotherUser = "4545";
                
        $gInterface = GeobitInterface::create(
                $this->em->getRepository('MallappGeobitsBundle:Geobit'),
                $this->em->getRepository('MallappGeobitsBundle:UserRetrieval'),
                $this->em
                );
        
        $bothParcels = new CoordinateBox(46.0, 6.0, 47.0, 8.0);
        $parcel1 = new CoordinateBox(46.0, 7.0, 47.0, 8.0);
        
                
        // Create 4 Geobits within two grid parcels (2 and 2)
        
        $gb1 = new Geobit(); // is in parcel 1 [46..47, 7..8]
        $gb1->setLatitude(46.81592)
            ->setLongitude(7.09579)
            ->setActive(true);
        
        $gb2 = new Geobit(); // is in parcel 1 [46..47, 7..8]
        $gb2->setLatitude(46.8387)
            ->setLongitude(7.00584)
            ->setActive(true);
        
        $gb3 = new Geobit(); // is in parcel 2 [46..47, 6..7]
        $gb3->setLatitude(46.83553)
            ->setLongitude(6.88224)
            ->setActive(true);
        
        $gb4 = new Geobit(); // is in parcel 2 [46..47, 6..7]
        $gb4->setLatitude(46.82167)
            ->setLongitude(6.85512)
            ->setActive(true);
        
        $gInterface->put($gb1);
        $gInterface->put($gb2);
        $gInterface->put($gb3);
        $gInterface->put($gb4);
        $gInterface->flushDB();

        // Wait for 2 seconds, otherwise, the put and ack are possibly in the same second, 
        // i.e. all items are returned.
        
        sleep(2);
        
        // Get all (cached) within both parcels
        $allInBothBefore = $gInterface->getChangedGeobitsInRect($bothParcels, false, $userToken);
                
        // Check if we received in fact all 4
        $this->assertCount(4, $allInBothBefore);
        
        // Acknowledge just one parcel
        $gInterface->ackGeobitRetrieval($parcel1, $userToken, new \DateTime());
        $gInterface->flushDB();
        
        // Get (again) all (cached) within both parcels
        $allInBothAfter = $gInterface->getChangedGeobitsInRect($bothParcels, false, $userToken);
        
        // Check if we received in fact only 2 out of 4
        $this->assertCount(2, $allInBothAfter);
        
        // Acknowledge both two parcels
        $gInterface->ackGeobitRetrieval($bothParcels, $userToken, new \DateTime());
        $gInterface->flushDB();
        
        // Get (again) all (cached) within both parcels
        $allInBothZero = $gInterface->getChangedGeobitsInRect($bothParcels, false, $userToken);
         
        // Check if we received none
        $this->assertCount(0, $allInBothZero);
        
        // Get (again) all (cached) within both parcels but with a different user token
        $allInBothDifferentUser = $gInterface->getChangedGeobitsInRect($bothParcels, false, $anotherUser);
         
        // Check if we received all 4 again
        $this->assertCount(4, $allInBothDifferentUser);
        
        // Update two (one in each parcel), set them to inactive
        $gb2->setActive(false);
        $gb3->setActive(false);
        $gInterface->flushDB();
        
        // Get (again) all (cached) within both parcels
        $allInBothChanged = $gInterface->getChangedGeobitsInRect($bothParcels, false, $userToken);
        
        // Check if we received in fact only the two changed ones.
        $this->assertCount(2, $allInBothChanged);
        
        // Get all (cached) but with force-reload
        $allInBothForce = $gInterface->getChangedGeobitsInRect($bothParcels, true, $userToken);
        
        // Check if we get all 4 again
        $this->assertCount(4, $allInBothForce);

        
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->createQuery('DELETE FROM MallappGeobitsBundle:Geobit')->execute();
        $this->em->createQuery('DELETE FROM MallappGeobitsBundle:UserRetrieval')->execute();
        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
    
}
