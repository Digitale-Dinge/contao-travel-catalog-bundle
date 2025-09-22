<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\DataContainer;

use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;

final readonly class TravelDataContainer
{

    public function loadDataContainer(string $table): void
    {
        if (TravelModel::getTable() !== $table) {
            return;
        }

        #$GLOBALS
    }

}