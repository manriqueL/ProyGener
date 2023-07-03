<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /*
    * Este metodo devuelve las entidades en el listado.
    * Como parametro recibe un array con los filtros que se
    * aplicarán
    */
    public function findForActionIndex($filtro)
    {
      $qb = $this->createQueryBuilder('e');

      // Este es un ejemplo de como se aplica un filtro.
      // El indice del array de $filtro hace referencia al valor que se aplicará
      // en este filtro si es que esta definido en el array y si no viene vacío
      if(isset($filtro["username"]) && $filtro["username"] != '') {
        $qb
          ->andWhere("e.username like :username")
          ->setParameter("username", '%'.$filtro["username"].'%')
        ;
      }

      return $qb;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
