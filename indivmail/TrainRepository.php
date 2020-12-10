<?php

namespace App\Repository;

use App\Entity\Train;
use App\Entity\Destination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Train|null find($id, $lockMode = null, $lockVersion = null)
 * @method Train|null findOneBy(array $criteria, array $orderBy = null)
 * @method Train[]    findAll()
 * @method Train[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Train::class);
    }

    /**
     * @return Train[]
     */
    public function findAll(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Train p
            ORDER BY p.price ASC'
        );

        return $query->getResult();
    }
}
