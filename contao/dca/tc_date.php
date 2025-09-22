<?php

declare(strict_types=1);

use Contao\DataContainer;
use Contao\DC_Table;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;

(static function (string $table): void {
    $GLOBALS['TL_DCA'][$table] = [
        'config' => [
            'dataContainer' => DC_Table::class,
            'enableVersioning' => true,
            'ptable' => TravelModel::getTable(),
            'doNotCopyRecords' => true,
            'sql' => [
                'keys' => [
                    'id' => 'primary',
                    'travel_code' => 'unique'
                ]
            ]
        ],
        'palettes' => [
            'default' => '
                price,departure,return;
                travel_code;
                published,start,stop;
            '
        ],
        'list' => [
            'label' => [
                'fields' => ['price', 'travel_code', 'departure', 'return'],
                'showColumns' => true,
                'showFirstOrderBy' => false
            ],
            'sorting' => [
                'mode' => DataContainer::MODE_SORTABLE,
                'panelLayout' => 'sort,filter;search,limit'
            ],
        ],
        'fields' => [
            'id' => [
                'sql' => "int(10) unsigned NOT NULL auto_increment"
            ],
            'tstamp' => [
                'sql' => "int(10) unsigned NOT NULL default '0'"
            ],
            'pid' => [
                'sql' => "int(10) unsigned NOT NULL default '0'"
            ],
            'price' => [
                'inputType' => 'text',
                'eval' => [
                    'mandatory' => true,
                    'rgxp' => 'digit',
                    'maxlength' => 7,
                    'tl_class' => 'w33'
                ],
                'sql' => "float(7,2) NOT NULL default '0.00'"
            ],
            'travel_code' => [
                'inputType' => 'text',
                'eval' => [
                    'unique' => true,
                    'maxlength' => 16,
                    'tl_class' => 'w33'
                ],
                'sql' => "varchar(16) NOT NULL default ''"
            ],
            'departure' => [
                'inputType' => 'text',
                'eval' => [
                    'mandatory' => true,
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w33 wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ],
            'return' => [
                'inputType' => 'text',
                'eval' => [
                    'mandatory' => true,
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w33 wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ],
            'published' => [
                'inputType' => 'checkbox',
                'toggle' => true,
                'eval' => [
                    'tl_class' => 'w33 m12'
                ],
                'sql' => "char(1) NOT NULL default ''"
            ],
            'start' => [
                'inputType' => 'text',
                'eval' => [
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w33 wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ],
            'stop' => [
                'inputType' => 'text',
                'eval' => [
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w33 wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ]
        ]
    ];
})($this->strTable);
