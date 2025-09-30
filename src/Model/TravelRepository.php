<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Contao\Date;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

readonly class TravelRepository
{

    public function __construct(private  Connection $connection)
    {
    }

    public function findAllPublishedByCategories(array $categories, int $page = 1, int $limit = 0): array
    {
        $qb = $this->getQueryBuilder($categories, $page, $limit);

        return $qb->executeQuery()->fetchAllAssociative();
    }

    public function countAllPublishedByCategories(array $categories = []): int
    {
        $qb = $this->getQueryBuilder($categories);

        $qb->select('COUNT(p.id)');

        return $qb->executeQuery()->fetchOne();
    }

    protected function getQueryBuilder(array $categories = [], int $page = 1, int $limit = 0): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('t.*', 'p.*')
            ->from(DateModel::getTable(), 'p')
            ->rightJoin('p', TravelModel::getTable(), 't', 'p.pid = t.id')
            ->where(
                't.published = 1',
                "t.start = '' OR t.start <= :now",
                "t.stop = '' OR t.stop > :now",
                'p.published = 1',
                "p.start = '' OR p.start <= :now",
                "p.stop = '' OR p.stop > :now",
            )->setParameter('now', Date::floorToMinute())
            ->setFirstResult(($page - 1) * $limit);

        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        if (count($categories) > 0) {
            $qb->andWhere('t.pid IN (:categories)');
            $qb->setParameter('categories', implode(',', $categories));
        }

        return $qb;
    }

}
