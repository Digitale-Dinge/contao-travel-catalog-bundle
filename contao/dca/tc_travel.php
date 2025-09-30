<?php

declare(strict_types=1);

use Contao\ContentModel;
use Contao\DataContainer;
use Contao\DC_Table;
use DigitaleDinge\TravelCatalogBundle\Model\CategoryModel;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;
use DigitaleDinge\TravelCatalogBundle\Model\RegionModel;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;
use Doctrine\DBAL\Types\Types;

(static function (string $table): void {
    $GLOBALS['TL_DCA'][$table] = [
        'config' => [
            'dataContainer' => DC_Table::class,
            'enableVersioning' => true,
            'ptable' => CategoryModel::getTable(),
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
                pid;
                name,alias,title,subtitle;
                meta_title,meta_description;
                countries,regions,description;
                image;
                published,start,stop;
            '
        ],
        'list' => [
            'label' => [
                'fields' => ['name', 'title', 'subtitle', 'countries', 'regions'],
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
                    'icon' => 'bundles/digitaledingetravelcatalog/icons/euro.svg',
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
            'pid' => [
                'inputType' => 'select',
                'foreignKey' => CategoryModel::fqid('name'),
                'eval' => [
                    'tl_class' => 'w50'
                ],
                'relation' => [
                    'type' => 'hasOne',
                    'load' => 'lazy',
                    'table' => TravelModel::getTable(),
                ],
                'sql' => [
                    'type' => Types::INTEGER,
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0,
                ]
            ],
            'tstamp' => [
                'sql' => "int(10) unsigned NOT NULL default '0'"
            ],
            'title' => [
                'inputType' => 'text',
                'search' => true,
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
                'search' => true,
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
            'countries' => [
                'inputType' => 'select',
                'filter' => true,
                'eval' => [
                    'multiple' => true,
                    'includeBlankOption' => true,
                    'chosen' => true,
                    'csv' => ',',
                    'tl_class' => 'w50'
                ],
                'sql' => [
                    'type' => Types::BLOB,
                    'notnull' => false
                ]
            ],
            'regions' => [
                'inputType' => 'select',
                'foreignKey' => RegionModel::fqid('name'),
                'filter' => true,
                'eval' => [
                    'multiple' => true,
                    'includeBlankOption' => true,
                    'chosen' => true,
                    'csv' => ',',
                    'tl_class' => 'w50'
                ],
                'sql' => [
                    'type' => Types::BLOB,
                    'notnull' => false
                ]
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
                    'extensions' => '%contao.image.valid_extensions%',
                    'tl_class' => 'clr'
                ],
                'sql' => "blob NULL"
            ],
            'published' => [
                'inputType' => 'checkbox',
                'toggle' => true,
                'filter' => true,
                'eval' => [
                    'tl_class' => 'w33 m12'
                ],
                'sql' => "char(1) NOT NULL default ''"
            ],
            'start' => [
                'inputType' => 'text',
                'sorting' => true,
                'eval' => [
                    'rgxp' => 'datim',
                    'datepicker' => true,
                    'tl_class' => 'w33 wizard'
                ],
                'sql' => "varchar(10) COLLATE ascii_bin NOT NULL default ''"
            ],
            'stop' => [
                'inputType' => 'text',
                'sorting' => true,
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
