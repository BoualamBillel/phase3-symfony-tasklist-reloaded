<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findTasksFiltered($user, ?string $status, ?string $priority): array
{
    $qb = $this->createQueryBuilder('t')
        ->leftJoin('t.priority', 'p')
        ->where('t.owner = :user')
        ->setParameter('user', $user);

    if ($status && $status !== 'Tous') {
        $qb->andWhere('t.status = :status')
            ->setParameter('status', $status);
    }

    if ($priority && $priority !== 'Toutes') {
        $qb->andWhere('p.level = :p_val') 
           ->setParameter('p_val', $priority);
    }

    return $qb->getQuery()->getResult();
}
}
