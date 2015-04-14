<?php

namespace Works\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 */
class Payment
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var integer
     */
    private $job_id;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Works\CommonBundle\Entity\Job
     */
    private $job;


    /**
     * Set hash
     *
     * @param string $hash
     * @return Payment
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set job_id
     *
     * @param integer $jobId
     * @return Payment
     */
    public function setJobId($jobId)
    {
        $this->job_id = $jobId;

        return $this;
    }

    /**
     * Get job_id
     *
     * @return integer 
     */
    public function getJobId()
    {
        return $this->job_id;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Payment
     */
    public function setCreatedAt()
    {
        if(!$this->getCreatedAt()) {
            $this->created_at = new \DateTime();
        }

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set job
     *
     * @param \Works\CommonBundle\Entity\Job $job
     * @return Payment
     */
    public function setJob(\Works\CommonBundle\Entity\Job $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return \Works\CommonBundle\Entity\Job 
     */
    public function getJob()
    {
        return $this->job;
    }
}
