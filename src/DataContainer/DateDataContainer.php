<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;
use Sqids\Sqids;

final readonly class DateDataContainer
{

    public function loadDataContainer(string $table): void
    {
        if (DateModel::getTable() !== $table) {
            return;
        }
    }

    #[AsCallback(DateModel::TABLE, 'fields.travel_code.save')]
    public function setTravelOnSave(?string $value, DataContainer $dc): string
    {
        if ($value) {
            return $value;
        }

        return new Sqids('ABCDEFGHKLMNPRSTUVWXYZ23456789', minLength: 4)->encode([$dc->id]);
    }

}