<?php

namespace App\Repository;

use App\Entity\Move;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Move|null find($id, $lockMode = null, $lockVersion = null)
 * @method Move|null findOneBy(array $criteria, array $orderBy = null)
 * @method Move[]    findAll()
 * @method Move[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Move::class);
  }

  /**
   * @return Move[];
   */
  public function findAllByCategoryId(int $id)
  {
    $connection = $this->getEntityManager()->getConnection();

    $sql = 'SELECT * FROM move_category WHERE category_id = :id';

    $stmt = $connection->prepare($sql);
    $stmt->execute(['id' => $id]);

    $data = $stmt->fetchAllAssociative();

    foreach ($data as $key => $item) {
      $moves[] = $this->find($item['move_id']);
    }

    return $moves;
  }
}
