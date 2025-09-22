<?php

declare(strict_types=1);

use Contao\ContentModel;
use Contao\DataContainer;
use Contao\DC_Table;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;

(static function (string $table): void {
    $GLOBALS['TL_DCA'][$table] = [
        'config' => [
            'dataContainer' => DC_Table::class,
            'enableVersioning' => true,
            'ctable' => [DateModel::getTable(), ContentModel::getTable()],
            'markAsCopy' => 'name',
            'sql' => [
                'keys' => [
                    'id' => 'primary'
                ]
            ]
        ],
        'palettes' => [
            'default' => '
                name,alias,title,subtitle;
                meta_title,meta_description;
                description;
                image,images;
                published,start,stop;
            '
        ],
        'list' => [
            'label' => [
                'fields' => ['title', 'name', 'published'],
                'showColumns' => true,
                'showFirstOrderBy' => false
            ],
            'sorting' => [
                'mode' => DataContainer::MODE_SORTABLE,
                'panelLayout' => 'sort,filter;search,limit'
            ],
            'operations' => [
                'edit',
                'edit_prices' => [
                    'primary' => true,
                    'href' => 'table=' . DateModel::getTable(),
                    'icon' => 'bundles/diditaledingecontaotravelcatalog/icons/euro.svg',
                ],
                'edit_content' => [
                    'primary' => true,
                    'href' => 'table=' . ContentModel::getTable(),
                    'icon' => 'children.svg',
                ],
                'copy',
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
            'title' => [
                'inputType' => 'text',
                'eval' => [
                    'maxlength' => 255,
                    'tl_class' => 'w50'
                ],
                'sql' => "varchar(255) NOT NULL default ''"
            ],
            'subtitle' => [
                'inputType' => 'text',
                'eval' => [
                    'maxlength' => 255,
                    'tl_class' => 'w50'
                ],
                'sql' => "varchar(255) NOT NULL default ''"
            ],
            'name' => [
                'inputType' => 'text',
                'eval' => [
                    'mandatory' => true,
                    'maxlength' => 255,
                    'tl_class' => 'w50'
                ],
                'sql' => "varchar(255) NOT NULL default ''"
            ],
            'alias' => [
                'inputType' => 'text',
                'eval' => [
                    'maxlength' => 255,
                    'tl_class' => 'w50'
                ],
                'sql' => "varchar(255) NOT NULL default ''"
            ],
            'description' => [
                'inputType' => 'textarea',
                'eval' => [
                    'rte' => 'tinyMCE',
                    'tl_class' => 'clr'
                ],
                'sql' => "text NULL"
            ],
            'meta_title' => [
                'inputType' => 'text',
                'eval' => [
                    'maxlength' => 255,
                    'tl_class' => 'w50'
                ],
                'sql' => "varchar(255) NOT NULL default ''"
            ],
            'meta_description' => [
                'inputType' => 'textarea',
                'eval' => [
                    'maxlength' => 255,
                    'tl_class' => 'w50'
                ],
                'sql' => "varchar(255) NOT NULL default ''"
            ],
            'image' => [
                'inputType' => 'fileTree',
                'eval' => [
                    'fieldType' => 'radio',
                    'files' => true,
                    'extensions' => 'jpg,jpeg,gif,png',
                    'tl_class' => 'clr'
                ],
                'sql' => "blob NULL"
            ],
            'images' => [
                'inputType' => 'fileTree',
                'eval' => [
                    'fieldType' => 'checkbox',
                    'files' => true,
                    'extensions' => 'jpg,jpeg,gif,png',
                    'tl_class' => 'clr'
                ],
                'sql' => "blob NULL"
            ],
            'travel_code' => [
                'inputType' => 'text',
                'eval' => [
                    'maxlength' => 16,
                    'tl_class' => 'w33'
                ],
                'sql' => "varchar(16) NOT NULL default ''"
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
