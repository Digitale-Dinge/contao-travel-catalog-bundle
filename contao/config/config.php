<?php

declare(strict_types=1);

use Contao\ContentModel;
use DigitaleDinge\TravelCatalogBundle\Model\CategoryModel;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;

$GLOBALS['BE_MOD']['content']['travel_catalog'] = [
    'tables' => [
        CategoryModel::getTable(),
        TravelModel::getTable(),
        DateModel::getTable(),
        ContentModel::getTable()
    ]
];

$GLOBALS['TL_MODELS'][CategoryModel::getTable()] = CategoryModel::class;
$GLOBALS['TL_MODELS'][TravelModel::getTable()] = TravelModel::class;
$GLOBALS['TL_MODELS'][DateModel::getTable()] = DateModel::class;