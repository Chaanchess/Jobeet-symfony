<?php
namespace App\Repository;

use App\Entity\Job;
use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository{
    
    /**
     * @param int $id
     * 
     * @return Job|null
     */
    public function findActiveJobs(int $id): ?Job{
       return $this->createQueryBuilder('j')
       ->where('j.id=:id')
       ->andWhere('j.expiresAt > :date')
       ->setParameter('id',$id)
       ->setParameter('date', new \DateTime())
       ->getQuery()
       ->getOneOrNullResult();
    }

}
?>
