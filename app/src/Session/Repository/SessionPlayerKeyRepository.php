<?php

declare(strict_types=1);

namespace App\Session\Repository;

use App\Session\Entity\SessionPlayerKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SessionPlayerKeyRepository extends ServiceEntityRepository implements SessionPlayerKeyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionPlayerKey::class);
    }

    public function findByKey(int $key): ?SessionPlayerKey
    {
        return $this->findOneBy(['key' => $key]);
    }
}
