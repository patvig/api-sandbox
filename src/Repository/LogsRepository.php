<?php

namespace App\Repository;

use App\Entity\Logs;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Logs>
 */
class LogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Logs::class);
    }

    public function logInterface($methode, $contrloleur, $rapport, $description) {
        $log = new Logs();
        //echo "<xmp>".print_r($rapport,1)."</xmp>";exit();
        $log->setMethode($methode)
            ->setControleur($contrloleur)
            ->setLog(json_encode($rapport))
            ->setDescription(json_encode($description))
            ->setDateExec(new DateTime());
        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();
    }
    //    /**
    //     * @return Logs[] Returns an array of Logs objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Logs
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
