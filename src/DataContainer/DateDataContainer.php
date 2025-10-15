<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;
use Sqids\Sqids;

final readonly class DateDataContainer
{

    #[AsCallback(DateModel::TABLE, 'config.oncopy')]
    public function setTravelCodeOnCopy(int $id, DataContainer $dc): void
    {
        if (null === $priceModel = DateModel::findByPk($id)) {
            throw new \RuntimeException(sprintf('The price model with ID "%s" does not exist.', $id));
        }

        $priceModel->code = $this->generateTravelCode($id);
        $priceModel->save();
    }

    #[AsCallback(DateModel::TABLE, 'fields.travel_code.save')]
    public function setTravelCodeOnSave(?string $value, DataContainer $dc): string
    {
        if ($value !== null) {
            return $value;
        }

        return $this->generateTravelCode($dc->id);
    }

    private function generateTravelCode(int $id): string
    {
        return new Sqids('ABCDEFGHKLMNPRSTUVWXYZ23456789', minLength: 4)->encode([$id]);
    }

}
