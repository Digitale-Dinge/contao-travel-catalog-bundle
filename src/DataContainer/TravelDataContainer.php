<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Intl\Countries;
use Contao\CoreBundle\Slug\Slug;
use Contao\DataContainer;
use Contao\DC_Table;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;
use Doctrine\DBAL\Connection;
use Sqids\Sqids;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class TravelDataContainer
{

    public function __construct(
        private Countries $countries,
        #[Autowire(service: 'contao.slug')]
        private Slug $slugGenerator,
        private Connection $connection,
    )
    {
    }

    #[AsCallback(TravelModel::TABLE, 'fields.alias.save')]
    public function setAliasOnSave(?string $value, DataContainer $dc): int|string
    {
        if (trim((string)$value) !== '') {
            return $value;
        }

        if (!$dc instanceof DC_Table) {
            return $dc->id;
        }

        $name = $dc->getActiveRecord()['name'] ?? '';

        if (trim($name) === '') {
            throw new \Exception('The travel name is required to generate an alias.');
        }

        return $this->generateAlias($name, $dc->id);
    }

    #[AsCallback(TravelModel::TABLE, 'fields.countries.options')]
    public function setCountyOptions(): array
    {
        return $this->countries->getCountries();
    }

    #[AsCallback(TravelModel::TABLE, 'config.oncopy')]
    public function setAliasOnCopy(int $id, DC_Table $dc): void
    {
        if (null === $model = TravelModel::findByPk($id)) {
            throw new \RuntimeException(sprintf('The travel model with ID "%s" does not exist.', $id));
        }

        $model->alias = $this->generateAlias($model->name, $id);
        $model->save();
    }

    private function generateAlias(string $name, int $id): string
    {
        $alias = $this->slugGenerator->generate($name);

        $statement = $this->connection
            ->prepare('SELECT COUNT(alias) FROM tc_travel WHERE alias = ?');
        $statement->bindValue(1, $alias);

        if ($statement->executeQuery()->fetchOne() === 0) {
            return $alias;
        }

        return sprintf('%s-%s', $alias, new Sqids('abcdefghijklmnopqrstuvwxyz', 4)->encode([$id]));
    }

}
