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
            'ptable' => TravelModel::getTable(),
            // 'doNotCopyRecords' => true,
            'sql' => [
                'keys' => [
                    'id' => 'primary',
                    'travel_code' => 'unique'
                ]
            ]
        ],
        'palettes' => [
            'default' => '
                travel_code,prices;
                departure,return,departure_text,return_text;
                published,start,stop;
            '
        ],
        'list' => [
            'label' => [
                'fields' => [
                    'travel_code',
                    'prices',
                    'departure',
                    'return',
                    'departure_text',
                    'return_text',
                ],
                'showColumns' => true,
                'showFirstOrderBy' => false
            ],
            'sorting' => [
                'mode' => DataContainer::MODE_SORTABLE,
                'panelLayout' => 'sort,filter;search,limit'
            ],
            'operations' => [
                'edit',
                'delete',
                'toggle',
                'show'
            ]
        ],
        'fields' => [
            'id' => [
                'sql' => "int(10) unsigned NOT NULL auto_increment"
            ],
            'tstamp' => [
                'sql' => "int(10) unsigned NOT NULL default '0'"
            ],
            'pid' => [
                'relation' => [
                    'table' => TravelModel::getTable(),
                    'type' => 'hasOne',
                    'load' => 'eager',
                ],
                'sql' => "int(10) unsigned NOT NULL default '0'"
            ],
            'travel_code' => [
                'inputType' => 'text',
                'search' => true,
                'eval' => [
                    'unique' => true,
                    'maxlength' => 16,
                    'doNotCopy' => true,
                    'tl_class' => 'w50'
                ],
                'sql' => "varchar(16) NULL"
            ],
            'prices' => [
                'inputType' => 'rowWizard',
                'exclude' => true,
                'fields' => [
                    'description' => [
                        'label' => &$GLOBALS['TL_LANG']['tc_date']['price_text'][0],
                        'inputType' => 'text',
                    ],
                    'price' => [
                        'label' => &$GLOBALS['TL_LANG']['tc_date']['price'][0],
                        'inputType' => 'text',
                        'eval' => [
                            'rgxp' => 'digit',
                            'maxlength' => 7,
                        ]
                    ],
                    'old_price' => [
                        'label' => &$GLOBALS['TL_LANG']['tc_date']['old_price'][0],
                        'inputType' => 'text',
                        'eval' => [
                            'rgxp' => 'digit',
                            'maxlength' => 7,
                        ],
                    ]
                ],
                'eval' => ['tl_class' => 'w50 clr', 'mandatory' => true],
                'sql' => 'text NULL',
            ],
            'departure' => [
                'inputType' => 'text',
                'search' => true,
                'filter' => true,
                'sorting' => true,
                'eval' => [
                    'mandatory' => true,
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w25 wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ],
            'return' => [
                'inputType' => 'text',
                'search' => true,
                'filter' => true,
                'sorting' => true,
                'eval' => [
                    'mandatory' => true,
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w25 wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ],
            'departure_text' => [
                'inputType' => 'text',
                'eval' => [
                    'maxlength' => 255,
                    'tl_class' => 'clr 1
                    w25'
                ],
                'sql' => [
                    'type' => Types::STRING,
                    'length' => 255,
                    'notnull' => true,
                    'default' => '',
                ]
            ],
            'return_text' => [
                'inputType' => 'text',
                'eval' => [
                    'maxlength' => 255,
                    'tl_class' => 'w25'
                ],
                'sql' => [
                    'type' => Types::STRING,
                    'length' => 255,
                    'notnull' => true,
                    'default' => '',
                ]
            ],
            'published' => [
                'inputType' => 'checkbox',
                'toggle' => true,
                'eval' => [
                    'tl_class' => 'w50'
                ],
                'sql' => "char(1) NOT NULL default ''"
            ],
            'start' => [
                'inputType' => 'text',
                'eval' => [
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w50 clr wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ],
            'stop' => [
                'inputType' => 'text',
                'eval' => [
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w50 wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ]
        ]
    ];
})($this->strTable);
