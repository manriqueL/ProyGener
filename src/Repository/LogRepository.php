<?php

namespace App\Repository;

use Gedmo\Loggable\Entity\LogEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LogEntry::class);
    }

    /*
    * Este metodo devuelve las entidades en el listado.
    * Como parametro recibe un array con los filtros que se
    * aplicaran
    */
    public function findForActionIndex($filtro = [])
    {
      $qb = $this->createQueryBuilder('e')
                ->orderBy("e.loggedAt", "DESC")
      ;


    if(isset($filtro["id"]) && trim($filtro["id"]) != '') {
        $qb
          ->andWhere("e.id = :id")
          ->setParameter("id", $filtro["id"])
        ;
      }

      if(isset($filtro["action"]) && trim($filtro["action"]) != '') {
          $qb
            ->andWhere("e.action like :action")
            ->setParameter("action", "%".$filtro["action"]."%")
          ;
      }

      if(isset($filtro["loggedAt"]) && trim($filtro["loggedAt"]) != '') {
          $qb
            ->andWhere("e.loggedAt like :loggedAt")
            ->setParameter("loggedAt", "%".$filtro["loggedAt"]."%")
          ;
      }

      if(isset($filtro["objectId"]) && trim($filtro["objectId"]) != '') {
          $qb
            ->andWhere("e.objectId = :objectId")
            ->setParameter("objectId", $filtro["objectId"])
          ;
      }

      if(isset($filtro["objectClass"]) && trim($filtro["objectClass"]) != '') {
          $qb
            ->andWhere("e.objectClass like :objectClass")
            ->setParameter("objectClass", "%".$filtro["objectClass"]."%")
          ;
      }

      if(isset($filtro["version"]) && trim($filtro["version"]) != '') {
          $qb
            ->andWhere("e.version = :version")
            ->setParameter("version", $filtro["version"])
          ;
      }

      if(isset($filtro["data"]) && trim($filtro["data"]) != '') {
          $qb
            ->andWhere("e.data like :data")
            ->setParameter("data", "%".$filtro["data"]."%")
          ;
      }

      if(isset($filtro["username"]) && trim($filtro["username"]) != '') {
          $qb
            ->andWhere("e.username = :username")
            ->setParameter("username", $filtro["username"])
          ;
      }

      return $qb;
    }

    // /**
    //  * @return Log[] Returns an array of Log objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Log
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
