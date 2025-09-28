<?php

declare(strict_types=1);

use Contao\ContentModel;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;

$GLOBALS['BE_MOD']['content']['travel_catalog'] = [
    'tables' => [TravelModel::getTable(), DateModel::getTable(), ContentModel::getTable()]
];

$GLOBALS['TL_MODELS']['tc_travel'] = TravelModel::class;
$GLOBALS['TL_MODELS']['tc_date'] = DateModel::class;