<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Contao\Date;

class RegionModel extends AbstractModel
{
    public const string TABLE = 'tc_region';

    protected static $strTable = self::TABLE;

    public \DateTimeInterface $lastUpdate {
        get => \DateTimeImmutable::createFromTimestamp($this->__get('tstamp'));
    }

    public string $name {
        get => $this->getRelated('pid')->name;
    }

    /**
     * @return self[]
     */
    public static function findAllPublished(array $options = []): array
    {
        return self::findAll(self::setPublishedOptions($options))?->getModels() ?? [];
    }

    public static function findPublishedByPid(int $pid, array $options = []): array
    {
        static $t = self::TABLE;

        $options['column'][] = "$t.pid = '$pid'";

        return static::findAllPublished($options);
    }

    private static function setPublishedOptions(array $options = []): array
    {
        if (!self::isPreviewMode($options)) {
            static $t = self::TABLE;
            static $time = Date::floorToMinute();

            $options['column'][] = "($t.start = '' OR $t.start <= $time)";
            $options['column'][] = "($t.stop = '' OR $t.stop > $time)";
            $options['column'][] = "($t.published = 1)";

            if ($options['eager'] ?? false) {
                $options['having'] = "(pid__start = '' OR pid__start <= $time)";
                $options['having'] .= " AND (pid__stop = '' OR pid__stop > $time)";
                $options['having'] .= " AND pid__published = 1";
            }
        }

        return $options;
    }

}
