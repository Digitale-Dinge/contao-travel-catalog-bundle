<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

class RegionModel extends AbstractModel
{
    public const string TABLE = 'tc_region';

    protected static $strTable = self::TABLE;

    public \DateTimeInterface $lastUpdate {
        get => \DateTimeImmutable::createFromTimestamp($this->__get('tstamp'));
    }

    public string $name {
        get => $this->__get('name');
    }

}
