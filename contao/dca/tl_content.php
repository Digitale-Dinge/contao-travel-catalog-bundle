<?php

declare(strict_types=1);

use DigitaleDinge\TravelCatalogBundle\Controller\ListController;

(static function (string $table): void {
    $GLOBALS['TL_DCA'][$table]['palettes'][ListController::TYPE] = '
        {title_legend},title,name,headline,type;
        jumpTo,perPage,numberOfItems,size;
        {template_legend:hide},customTpl;
        {protected_legend:hide},protected;
        {expert_legend:hide},guests,cssID
    ';
})($this->strTable);