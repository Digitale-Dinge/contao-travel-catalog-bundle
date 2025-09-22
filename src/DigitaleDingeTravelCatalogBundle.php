<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Override;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class DigitaleDingeTravelCatalogBundle extends AbstractBundle implements BundlePluginInterface
{

    #[Override]
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
    }

    #[Override]
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(self::class)->setLoadAfter([ContaoCoreBundle::class])
        ];
    }

}
