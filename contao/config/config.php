<?php

declare(strict_types=1);

use Contao\ContentModel;
use DigitaleDinge\TravelCatalogBundle\Model\CategoryModel;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;
use DigitaleDinge\TravelCatalogBundle\Model\RegionModel;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;

$GLOBALS['BE_MOD']['travel_catalog'] = [
    'catalog' => [
        'tables' => [
            CategoryModel::getTable(),
            TravelModel::getTable(),
            DateModel::getTable(),
            ContentModel::getTable()
        ]
    ],
    'regions' => [
        'tables' => [RegionModel::getTable()]
    ]

];

$GLOBALS['TL_MODELS'][CategoryModel::getTable()] = CategoryModel::class;
$GLOBALS['TL_MODELS'][TravelModel::getTable()] = TravelModel::class;
$GLOBALS['TL_MODELS'][DateModel::getTable()] = DateModel::class;
$GLOBALS['TL_MODELS'][RegionModel::getTable()] = RegionModel::class;