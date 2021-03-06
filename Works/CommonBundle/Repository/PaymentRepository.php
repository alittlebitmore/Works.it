<?php

namespace Works\CommonBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PaymentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaymentRepository extends EntityRepository
{
    public function commitPayment($hash)
    {
        $qb = $this->createQueryBuilder('p')
                ->select(array('p', 'j'))
                ->where('p.hash = :hash')
                ->leftJoin('p.job', 'j')
                ->setParameter('hash', $hash);

        $result = $qb->getQuery()->getOneOrNullResult();
        if($result) {
            $em = $this->getEntityManager();
            $job = $result->getJob();
            $job->setType(2);
            $em->persist($job);
            $em->remove($result);
            $em->flush();
        }
    }
    
    public function checkHash($job)
    {
        $qb = $this->createQueryBuilder('p')
                ->where('p.job_id = :job')
                ->setParameter('job', $job);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
