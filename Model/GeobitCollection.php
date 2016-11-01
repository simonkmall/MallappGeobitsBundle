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
 * A set of static functions to interact with the Geobits and UserRetrievals
 * which are stored in a doctrine database. Need to provide the repository for
 * that reason.
 *
 * @author Simon Mall
 */
class GeobitCollection {
    
    
    public static function getGeobitsInBoundingBox(CoordinateBox $boundingBox,
            $cached, $userToken, EntityRepository $geobitsRepo, 
            EntityRepository $retrievalsRepo, ObjectManager $em) {
        

        // Assume for now that we have to retrieve all geobits
        
        $retrieveAllSince = \DateTime::createFromFormat(\DateTime::ISO8601, "2000-01-01T00:00:00+0000");
        
        // (i) create or (ii) get & update the last retrieval
        
        $lastUserRetrieval = $retrievalsRepo->findOneByRetrievedBy($userToken);
        
        if ($lastUserRetrieval == null) {
                
                // User did not yet retrieve anything, so create it in DB
            
                $lastUserRetrieval = new UserRetrieval();
                $lastUserRetrieval->setRetrievedAt(new \DateTime())
                        ->setRetrievedBy($userToken);
                
                $em->persist($lastUserRetrieval);
                
        }
        else {
            
            // User did retrieve in the past, so get the date 
            // (at least if we are caching) and update it.
            
            if ($cached == true) {
                
                $retrieveAllSince = $lastUserRetrieval->getRetrievedAt();
                
            }
            
            $lastUserRetrieval->setRetrievedAt(new \DateTime());
                
        }
        

        // Persist changes into DB
        $em->flush();
        
        // Now actually create the query
        
        $query = $geobitsRepo->createQueryBuilder('d');
        
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
                
        $query->andWhere('d.active = 1')
            ->add('where', $query->expr()->gte(
                    'd.changedAt',
                    ':lastRetrieved'
                    ))
            ->setParameter('lastRetrieved', $lastUserRetrieval)
            ->setParameter('NWLong', $boundingBox->getNorthWest()->getLongitude())
            ->setParameter('SELong', $boundingBox->getSouthEast()->getLongitude())
            ->setParameter('NWLat', $boundingBox->getNorthWest()->getLatitude())
            ->setParameter('SELat', $boundingBox->getSouthEast()->getLatitude())
            ->getQuery();
    	 
        return $query->getResult();
        
    }
    
    
}
