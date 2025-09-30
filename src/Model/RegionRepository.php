<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Doctrine\DBAL\Connection;

readonly class RegionRepository
{

    public function __construct(private Connection $connection)
    {
    }

    public function findAllUsedByTravels(): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('r.id, r.name')
            ->distinct()
            ->from('tc_region', 'r')
            ->innerJoin('r', 'tc_travel', 't')
            ->where('FIND_IN_SET(r.id, t.regions) > 0')
            ->orderBy('r.name');

        return $qb->executeQuery()->fetchAllAssociative();
    }

}
