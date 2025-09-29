<?php

declare(strict_types=1);

use Contao\DataContainer;
use Contao\DC_Table;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;
use Doctrine\DBAL\Types\Types;

(static function (string $table): void {
    $GLOBALS['TL_DCA'][$table] = [
        'config' => [
            'dataContainer' => DC_Table::class,
            'enableVersioning' => true,
            'ctable' => [TravelModel::getTable()],
            'sql' => [
                'keys' => [
                    'id' => 'primary'
                ]
            ]
        ],
        'list' => [
            'sorting' => [
                'mode' => DataContainer::MODE_SORTED,
                'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
                'fields' => ['name'],
                'panelLayout' => 'sort,filter;search,limit'
            ],
            'label' => [
                'fields' => ['name'],
                'showColumns' => true,
            ]
        ],
        'palettes' => [
            'default' => 'name'
        ],
        'fields' => [
            'id' => [
                'sql' => [
                    'type' => Types::INTEGER,
                    'unsigned' => true,
                    'autoincrement' => true
                ]
            ],
            'tstamp' => [
                'sql' => [
                    'type' => Types::INTEGER,
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0,
                ]
            ],
            'name' => [
                'inputType' => 'text',
                'eval' => [
                    'mandatory' => true,
                    'maxlength' => 255,
                    'tl_class' => 'w50'
                ],
                'sql' => [
                    'type' => Types::STRING,
                    'length' => 255,
                    'notnull' => true,
                    'default' => '',
                ]
            ]
        ]
    ];
})($this->strTable);
