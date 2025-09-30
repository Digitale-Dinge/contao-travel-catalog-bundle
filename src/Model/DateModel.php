<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Contao\Date;
use Contao\FilesModel;

class DateModel extends AbstractModel
{
    public const string TABLE = 'tc_date';

    protected static $strTable = self::TABLE;

    public \DateTimeInterface $lastUpdate {
        get => \DateTimeImmutable::createFromTimestamp($this->__get('tstamp'));
    }

    public string $code {
        get => $this->__get('travel_code');
    }

    public string $name {
        get => $this->getRelated('pid')->name;
    }

    public string $alias {
        get => $this->getRelated('pid')->alias;
    }

    public string $title {
        get => $this->getRelated('pid')->title;
    }

    public string $subtitle {
        get => $this->getRelated('pid')->subtitle;
    }

    public array $countries {
        get => $this->getRelated('pid')->countries;
    }

    public string $description {
        get => $this->getRelated('pid')->description ?? '';
    }

    public ?string $content {
        get => $this->getRelated('pid')->content;
    }

    public ?FilesModel $image {
        get => $this->getRelated('pid')->image;
    }

    public \DateTimeInterface $departure {
        get => \DateTimeImmutable::createFromTimestamp((int)$this->__get('departure'));
    }

    public \DateTimeInterface $return {
        get => \DateTimeImmutable::createFromTimestamp((int)$this->__get('return'));
    }

    public bool $isOneDayTrip {
        get {
            return $this->departure->format('Y-m-d') === $this->return->format('Y-m-d');
        }
    }

    public ?string $href = null;

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

    public static function findPublishedByCategories(array $categories, array $options = []): array
    {
        $options['eager'] = true;
        $options = self::setPublishedOptions($options);

        $options['having'] .= " AND pid__pid IN (" . implode(',', $categories) . ")";

        return static::findAll($options)?->getModels() ?? [];
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
