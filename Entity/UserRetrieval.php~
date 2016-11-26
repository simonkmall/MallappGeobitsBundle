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

namespace Mallapp\GeobitsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRetrievals
 *
 * @ORM\Table(name="user_retrievals")
 * @ORM\Entity(repositoryClass="Mallapp\GeobitsBundle\Repository\UserRetrievalsRepository")
 */
class UserRetrieval
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
     /**
     * @var string
     *
     * @ORM\Column(name="parce_lid", type="string", length=255, unique=true)
     */
    private $parcelId;   

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="retrieved_at", type="datetime")
     */
    private $retrievedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="retrieved_by", type="string", length=255, unique=false)
     */
    private $retrievedBy;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set retrievedAt
     *
     * @param \DateTime $retrievedAt
     *
     * @return UserRetrievals
     */
    public function setRetrievedAt($retrievedAt)
    {
        $this->retrievedAt = $retrievedAt;

        return $this;
    }

    /**
     * Get retrievedAt
     *
     * @return \DateTime
     */
    public function getRetrievedAt()
    {
        return $this->retrievedAt;
    }

    /**
     * Set retrievedBy
     *
     * @param string $retrievedBy
     *
     * @return UserRetrievals
     */
    public function setRetrievedBy($retrievedBy)
    {
        $this->retrievedBy = $retrievedBy;

        return $this;
    }

    /**
     * Get retrievedBy
     *
     * @return string
     */
    public function getRetrievedBy()
    {
        return $this->retrievedBy;
    }

    /**
     * Set parcelId
     *
     * @param string $parcelId
     *
     * @return UserRetrieval
     */
    public function setParcelId($parcelId)
    {
        $this->parcelId = $parcelId;

        return $this;
    }

    /**
     * Get parcelId
     *
     * @return string
     */
    public function getParcelId()
    {
        return $this->parcelId;
    }
}
