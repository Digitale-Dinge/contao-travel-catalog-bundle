<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Contao\Model;

class TravelModel extends Model
{
    public const string TABLE = 'tc_travel';

    protected static $strTable = self::TABLE;

}
