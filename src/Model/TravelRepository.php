<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\Date;
use DigitaleDinge\TravelCatalogBundle\FormData\FilterData;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class TravelRepository
{

    public function __construct(
        private Connection $connection,
        private RequestStack $requestStack,
        private ScopeMatcher $scopeMatcher,
    )
    {
    }

    public function findAllPublished(FilterData $filterData): array
    {
        $qb = $this->getQueryBuilder($filterData);

        return $qb->executeQuery()->fetchAllAssociative();
    }

    public function findAllCountriesFromPublishedTravelsByCategories(array $categories = []): array
    {
        $filterData = new FilterData;
        $filterData->categories = $categories;

        $qb = $this->getQueryBuilder($filterData);
        $result = $qb->select('t.countries')
            ->distinct()
            ->fetchAllAssociative();

        $arr = array_column($result, 'countries');

        // remove null value
        $arr = array_filter($arr, fn($s) => $s !== null);
        
        return array_values(array_unique(array_merge(
            ...array_map(fn($s) => array_map('trim', explode(',', $s)), $arr)
        )));
    }

    public function countAllPublished(FilterData $filterData): int
    {
        $qb = $this->getQueryBuilder($filterData);

        $qb->select('COUNT(p.id)');

        return $qb->executeQuery()->fetchOne();
    }

    protected function getQueryBuilder(FilterData $filterData): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('p.id')
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
            ->setFirstResult(($filterData->page - 1) * $filterData->perPage);

        if ($filterData->categories !== []) {
            $qb->andWhere('FIND_IN_SET(t.pid, :categories)');
            $qb->setParameter('categories', implode(',', $filterData->categories));
        }

        if ($filterData->name) {
            $qb->andWhere($qb->expr()->or(
                $qb->expr()->like('t.name', ':name'),
                $qb->expr()->eq('p.travel_code', ':travel_code'),
            ));
            $qb->setParameter('name', '%' . $filterData->name . '%');
            $qb->setParameter('travel_code', $filterData->name);
        }

        if ($filterData->oneDayTrip) {
            $qb->andWhere('DATE_FORMAT(FROM_UNIXTIME(p.departure), "%Y-%m-%d") = DATE_FORMAT(FROM_UNIXTIME(p.return), "%Y-%m-%d")');
        }

        if ($filterData->date) {
            $qb->andWhere('DATE_FORMAT(FROM_UNIXTIME(p.departure), "%Y-%m-%d") = :date');
            $qb->setParameter('date', $filterData->date->format('Y-m-d'));
        }

        if ($filterData->country !== []) {
            $ors = [];
            foreach ($filterData->country as $countryCode) {
                $ors[] = "FIND_IN_SET('$countryCode', t.countries) > 0";
            }

            $qb->andWhere($qb->expr()->or(...$ors));

            unset($ors);
        }

        if ($filterData->region !== []) {
            $ors = [];
            foreach ($filterData->region as $regionId) {
                $regionId = (int)$regionId;
                $ors[] = "FIND_IN_SET($regionId, t.regions) > 0";
            }
            $qb->andWhere($qb->expr()->or(...$ors));

            unset($ors);
        }

        if ($filterData->perPage > 0) {
            $qb->setMaxResults($filterData->perPage);
        }

        $qb->orderBy('p.departure', 'ASC');

        return $qb;
    }

}
