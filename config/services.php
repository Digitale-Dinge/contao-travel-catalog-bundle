<?php

declare(strict_types=1);

use DigitaleDinge\TravelCatalogBundle\Controller\DetailController;
use DigitaleDinge\TravelCatalogBundle\Controller\FilterController;
use DigitaleDinge\TravelCatalogBundle\Controller\ListController;
use DigitaleDinge\TravelCatalogBundle\DataContainer\DateDataContainer;
use DigitaleDinge\TravelCatalogBundle\DataContainer\TravelDataContainer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @formatter:off
 */
return static function (ContainerConfigurator $container): void {
    $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()

        ->set(DateDataContainer::class)
        ->set(TravelDataContainer::class)
        ->set(ListController::class)
        ->set(FilterController::class)
        ->set(DetailController::class)
    ;
};
