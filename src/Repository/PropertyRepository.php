<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @return Query
     */
    public function findAllVisibleQuery(): Query
    {
        // On crée un querybuilder, un objet qui a pour but de concevoir une requete on lui donne un alias ici 'p' on devra utiliser p pour faire
        // référence à la table qui contient les properties
        return $this->findVisibleQuery()
            ->getQuery()  // Permet de récupérer la requete une fois celle ci parametrer comme on le veut
               // Permet de récupérer les résultats
        ;
    }
    /**
     * @return Property[]
     */
    public function findLatest(): array
    {
        return $this->findVisibleQuery()
        ->setMaxResults(4)
        ->getQuery()  // Permet de récupérer la requete une fois celle ci parametrer comme on le veut
        ->getResult()   // Permet de récupérer les résultats
        ;
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*

    // EXEMPLE DE PARAMETRE DE REQUETE !!! ----------------------------------------------------

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // ON PLACE LES METHODES PRIVATE EN BAS CAR ON NE LES CONSULTE JAMAIS

    // On crée une méthode générique afin de faire une requete de base avec un seul parametre modifiable ici sold=false mais pourrais etre les biens 
    // des 7 derniers jours
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.sold = false');
    }
}
