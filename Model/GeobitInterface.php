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

use Mallapp\GeobitsBundle\Entity\Geobit;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;

/**
 *
 * @author Simon Mall
 */
class GeobitInterface {
    
    private $syncCache;
    
    public function __construct(GeobitSyncCache $syncCache) {
        $this->syncCache = $syncCache;
    }

    /**
     * Stores a geobit in the database. DOES NOT FLUSH DB (call flushDB()).
     * @param Geobit $geobit
     */
    public function put(Geobit $geobit) {
        
        $geobit->setGeneratedAt(new \DateTime());
        $geobit->setChangedAt(new \DateTime());
        
        $this->syncCache->getEm()->persist($geobit);
        
    }
    
    /**
     * Sets active to the value given for the given geobit. 
     * DOES NOT FLUSH DB (call flushDB()).
     * @param Geobit $geobit
     */
    public function setActive(Geobit $geobit, $active) {
    
        $geobit->setActive($active);
        $geobit->setChangedAt(new \DateTime());
        
    }
    
    /**
     * Sets active to the given value for all geobits in the array given.
     * DOES NOT FLUSH DB (call flushDB()).
     * @param Array $geobitArray
     * @param boolean $active
     */
    public function setActiveForGeobitArray($geobitArray, $active) {
        
        foreach ($geobitArray as $geobit) {
            
            $this->setActive($geobit, $active);
            
        }
        
    }
    
    /**
     * Acknowledge that the geobits were retrieved and stored correctly on the client device.
     * DOES NOT FLUSH DB (call flushDB()).
     * @param \Mallapp\GeobitsBundle\Model\CoordinateBox $boundingBox
     * @param type $userToken
     */
    public function ackGeobitRetrieval(CoordinateBox $boundingBox, $userToken, $retrievalDate) {
        
        $syncGrid = new GeobitSyncGrid($boundingBox);
        
        $this->syncCache->ackGeobitsInsideSyncGridBox($syncGrid, $userToken, $retrievalDate);
        
    }
    
    /**
     * Persists all changes to the database. Must be called manually after all
     * Necessary changes were made.
     */
    public function flushDB() {
        
        $this->syncCache->getEm()->flush();
    }
    
    
    /**
     * Returns an array containing all Geobits within a specific circle.
     * Note that the SyncCache is bypassed, i.e. always all geobits are returned.
     * @param \Mallapp\GeobitsBundle\Model\CoordinateCircle $circle
     * @return Array Array of Geobit objects
     */
    public function getAllGeobitsInCircle(CoordinateCircle $circle) {
        
        $candidates = $this->getAllGeobitsInRect($circle->getBoundingBox());
        
        $containedInCircle = Array();
        
        foreach ($candidates as $candidate) {
            
            if ($circle->containsCoordinate(new Coordinate($candidate->latitude, $candidate->longitude))) {
                
                $containedInCircle[] = $candidate;
                
            }
            
        }
        
        return $containedInCircle;
        
    }
    
    /**
     * Returns an array containing all Geobits within a specific rectangle.
     * Note that the SyncCache is bypassed, i.e. always all geobits are returned.
     * Note that the coordinateBox is automatically extended to the nearest Gridpoints,
     * As no out-of-grid-retrievals are allowed.
     * @param \Mallapp\GeobitsBundle\Model\CoordinateBox $coordinateBox
     * @return Array Array of Geobit objects
     */
    public function getAllGeobitsInRect(CoordinateBox $coordinateBox) {
        
        $syncGrid = new GeobitSyncGrid($coordinateBox);
        
        // Note that the userToken is irrelevant if forceReload is set to true.
        return $this->syncCache->getGeobitsInsideSyncGridBox($syncGrid, "", true);

        
    }
    
    /**
     * Returns an array containing all Geobits within a specific rectangle.
     * Note that the SyncCache is used, i.e. only the geobits changed after the
     * last retrieval of this user are returned.
     * @param \Mallapp\GeobitsBundle\Model\CoordinateBox $boundingBox
     * @param Boolean $forceReload
     * @param String $userToken
     * @return Array Array of Geobit objects
     */
    public function getChangedGeobitsInRect(CoordinateBox $boundingBox, $forceReload, $userToken) {
        
        $syncGrid = new GeobitSyncGrid($boundingBox);
        
        return $this->syncCache->getGeobitsInsideSyncGridBox($syncGrid, $userToken, $forceReload);
        
    }
    
    /**
     * Static factory function for creation of a GeobitInterface object.
     * @param EntityRepository $geobitsRepo
     * @param EntityRepository $retrievalsRepo
     * @param ObjectManager $em
     * @return \Mallapp\GeobitsBundle\Model\GeobitInterface
     */
    public static function create(EntityRepository $geobitsRepo, 
            EntityRepository $retrievalsRepo, ObjectManager $em) {
        

        $cache = new GeobitSyncCache($geobitsRepo, $retrievalsRepo, $em);
        
        $interface = new GeobitInterface($cache);
        
        return $interface;
        
    }
    
    
    
}
