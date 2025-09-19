<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Contao\Model;

class DateModel extends Model
{
    public const string TABLE = 'tc_date';

    protected static $strTable = self::TABLE;

}
