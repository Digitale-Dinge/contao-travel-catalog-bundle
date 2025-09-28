<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Intl\Countries;
use Contao\CoreBundle\Slug\Slug;
use Contao\DataContainer;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class TravelDataContainer
{

    public function __construct(
        private Countries $countries,
        #[Autowire(service: 'contao.slug')]
        private Slug $slugGenerator,
    )
    {
    }

    #[AsHook('loadDataContainer')]
    public function loadDataContainer(string $table): void
    {
        if (TravelModel::getTable() !== $table) {
            return;
        }
    }

    #[AsCallback(TravelModel::TABLE, 'fields.alias.save')]
    public function setAliasOnSave(?string $value, DataContainer $dc): string
    {
        if ($value) {
            return $value;
        }

        return $this->slugGenerator->generate($dc->getCurrentRecord()['name']);
    }

    #[AsCallback(TravelModel::TABLE, 'fields.countries.options')]
    public function setCountyOptions(): array
    {
        return $this->countries->getCountries();
    }

}
