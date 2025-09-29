<?php

declare(strict_types=1);

use DigitaleDinge\TravelCatalogBundle\Controller\ListController;
use DigitaleDinge\TravelCatalogBundle\Model\CategoryModel;

(static function (string $table): void {
    $GLOBALS['TL_DCA'][$table]['fields']['tc_categories'] = [
        'inputType' => 'select',
        'foreignKey' => CategoryModel::fqid('name'),
        'eval' => [
            'multiple' => true,
            'chosen' => true,
            'tl_class' => 'w50'
        ],
        'sql' => "text NULL"
    ];

    $GLOBALS['TL_DCA'][$table]['palettes'][ListController::TYPE] = '
        {title_legend},title,name,headline,type;
        tc_categories;
        jumpTo,perPage,numberOfItems,size;
        {template_legend:hide},customTpl;
        {protected_legend:hide},protected;
        {expert_legend:hide},guests,cssID
    ';
})($this->strTable);