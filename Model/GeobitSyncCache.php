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

use Mallapp\GeobitsBundle\Entity\UserRetrieval;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of GeobitSyncCache
 *
 * @author Simon Mall
 */
class GeobitSyncCache {
    

    private $geobitsRepo;
    
    private $retrievalsRepo;
    
    private $em;
    
    public function __construct(EntityRepository $geobitsRepo, 
            EntityRepository $retrievalsRepo, ObjectManager $em) {
    

        $this->geobitsRepo = $geobitsRepo;
        
        $this->retrievalsRepo = $retrievalsRepo;
        
        $this->em = $em;
        
    }
    
    public function getGeobitsInsideSyncGridBox(GeobitSyncGrid $syncGrid, $userToken, $forceReload = false) {
        
        // Prepare the array to return
        $arrayOfAllGeobits = Array();
        
        // Loop through all grid parcels
        foreach($syncGrid->getIterator() as $syncGridElement) {
            
            // Get the date when this user retrieved this parcel's data for the last time
            
            $retrieveAllSince = $this->getRetrieveAllSinceDate($syncGridElement->getUniqueId(), $userToken, $forceReload);
            
            // Append all geobits changed after that date to the array
            
            $this->getGeobitsWithinBoxChangedLaterThan($syncGridElement->getCoordinateBox(), $retrieveAllSince, $arrayOfAllGeobits);
            
        }

        return $arrayOfAllGeobits;
        
    }
    
    public function ackGeobitsInsideSyncGridBox(GeobitSyncGrid $syncGrid, $userToken, $retrievalDate) {
        
        
        // Loop through all grid parcels
        foreach($syncGrid->getIterator() as $syncGridElement) {
            
            $this->putRetrieval($syncGridElement->getUniqueId(), $userToken, $retrievalDate);
            
        }
        
    }
    

    
    private function getRetrieveAllSinceDate($parcel, $userToken, $forceReload) {
        
        // Assume for now that we have to retrieve all geobits

        $retrieveAllSince = \DateTime::createFromFormat(\DateTime::ISO8601, "2000-01-01T00:00:00+0000");

        // get the last retrieval

        $lastUserRetrieval = $this->retrievalsRepo->findOneBy(
                array('retrievedBy' => $userToken, 'parcelId' => $parcel)
                );

        if ($lastUserRetrieval != null) {

            // User did retrieve in the past, so get the date 
            // (at least if we are not force-reloading).

            if ($forceReload == false) {

                $retrieveAllSince = $lastUserRetrieval->getRetrievedAt();

            }

        }
        
        return $retrieveAllSince;
        
            
    }
    
    private function putRetrieval($parcel, $userToken, $retrievalDate) {
        
        // Try to get the retrieval from the DB in case if exists.

        $lastUserRetrieval = $this->retrievalsRepo->findOneBy(
                array('retrievedBy' => $userToken, 'parcelId' => $parcel)
                );

        if ($lastUserRetrieval == null) {

                // Does not exist, so create

                $lastUserRetrieval = new UserRetrieval();
                $lastUserRetrieval->setRetrievedAt($retrievalDate)
                        ->setRetrievedBy($userToken)
                        ->setParcelId($parcel);

                $this->em->persist($lastUserRetrieval);

        }
        else {

            // Exists, so update

            $lastUserRetrieval->setRetrievedAt(new \DateTime());

        }

        // IMPORTANT: Note that the db changes must be persisted outside this function!
        
    }
    
    private function getGeobitsWithinBoxChangedLaterThan($boundingBox, $changedAfter, &$resultArray) {
        
        // Create the query
        $query = $this->geobitsRepo->createQueryBuilder('d');
        
        if ($boundingBox->getFlipAroundLong()) {
            
            $query->add('where', 
                $query->expr()->andX(
                    $query->expr()->orX(
                        $query->expr()->gte('d.longitude',':NWLong'),
                        $query->expr()->lte('d.longitude',':SELong')
                    ),
                    $query->expr()->andX(
                        $query->expr()->gte('d.latitude',':SELat'),
                        $query->expr()->lte('d.latitude',':NWLat')
                    )
                )
            );
            
        }
        else {
            
            $query->add('where', 
                $query->expr()->andX(
                    $query->expr()->andX(
                        $query->expr()->gte('d.longitude',':NWLong'),
                        $query->expr()->lte('d.longitude',':SELong')
                    ),
                    $query->expr()->andX(
                        $query->expr()->gte('d.latitude',':SELat'),
                        $query->expr()->lte('d.latitude',':NWLat')
                    )
                )
            );
            
        }
                
        $query->andWhere($query->expr()->gte(
                    'd.changedAt',
                    ':lastRetrieved'
                    ));
        
        $query->setParameter('lastRetrieved', $changedAfter)
            ->setParameter('NWLong', $boundingBox->getNorthWest()->getLongitude())
            ->setParameter('SELong', $boundingBox->getSouthEast()->getLongitude())
            ->setParameter('NWLat', $boundingBox->getNorthWest()->getLatitude())
            ->setParameter('SELat', $boundingBox->getSouthEast()->getLatitude());
        
        foreach($query->getQuery()->getResult() as $oneElement) {
            
            $resultArray[] = $oneElement;
            
        }
        
    }

    public function getGeobitsRepo() {
        return $this->geobitsRepo;
    }

    public function getRetrievalsRepo() {
        return $this->retrievalsRepo;
    }

    public function getEm() {
        return $this->em;
    }


    
}
