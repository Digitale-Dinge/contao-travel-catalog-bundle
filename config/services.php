<?php

declare(strict_types=1);

use DigitaleDinge\TravelCatalogBundle\DataContainer\TravelDataContainer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set(TravelDataContainer::class);
};
