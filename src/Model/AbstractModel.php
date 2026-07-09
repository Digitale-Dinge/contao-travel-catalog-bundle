<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Contao\Model;

abstract class AbstractModel extends Model
{
    final public static function fqid(string $field, ?string $sorting = null): string
    {
        return sprintf('`%s`.`%s` %s',
            self::getTable(),
            $field,
            $sorting ?? ''
        );
    }

    final public static function foreignKey(string $field): string
    {
        return sprintf('%s.%s', self::getTable(), $field);
    }
}
