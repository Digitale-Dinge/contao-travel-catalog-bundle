<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Doctrine\DBAL\Connection;

class TravelRepository
{

    public function __construct(private readonly Connection $connection)
    {
    }

}
