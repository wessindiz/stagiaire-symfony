<?php

namespace App\Repository;

use App\Entity\Stagiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stagiaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stagiaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stagiaire[]    findAll()
 * @method Stagiaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StagiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stagiaire::class);

    }

    // /**
    //  * @return Stagiaire[] Returns an array of Stagiaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stagiaire
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllStagiaireByName($name):array
    {
            $db = $this->getEntityManager()->getConnection();
            $req = '
                SELECT * FROM stagiaire AS s 
                WHERE s.nom LIKE :n
                OR s.prenom LIKE :n
                ';
            $result = $db->prepare($req);
            $result->execute(['n'=>'%'.$name.'%']);
            return $result->fetchAllAssociative();
    }

    

    /*public function findAllGreaterThanPrice($name):array
    {
        return $this->createQueryBuilder('s')
                    ->andWhere('s.name > :n')
                    ->setParameter('n',$name)
                    ->orderBy('s.name','DESC')
                    ->getQuery()
                    ->getResult()
        ;
    }*/

}
