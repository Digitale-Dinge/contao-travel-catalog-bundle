<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Model;

use Contao\ContentModel;
use Contao\Controller;
use Contao\Date;
use Contao\FilesModel;
use Contao\Model;
use Contao\System;

class TravelModel extends Model
{
    public const string TABLE = 'tc_travel';

    protected static $strTable = self::TABLE;

    protected(set) int $id {
        get => $this->__get('id');
        set {
            $this->__set('id', $value);
        }
    }

    public \DateTimeInterface $lastUpdate {
        get => \DateTimeImmutable::createFromTimestamp($this->__get('tstamp'));
    }

    public string $name {
        get => $this->__get('name') ?? '';
    }

    public ?string $alias {
        get => $this->__get('alias');
        set {
            $this->__set('alias', $value);
        }
    }

    public string $title {
        get => $this->__get('title') ?? '';
    }

    public string $subtitle {
        get => $this->__get('subtitle') ?? '';
    }

    public string $description {
        get => $this->__get('description') ?? '';
    }

    public ?string $metaTitle {
        get => $this->__get('metaTitle');
    }

    public ?string $metaDescription {
        get => $this->__get('meta_description');
    }

    public ?FilesModel $image {
        get => FilesModel::findByUuid($this->__get('image'));
    }

    public array $countries {
        get {
            if (null === $this->__get('countries')) {
                return [];
            }

            static $countries = System::getContainer()->get('contao.intl.countries')->getCountries();

            $currentCountries = array_flip(explode(',', $this->__get('countries')));

            return array_intersect_key($countries, $currentCountries);
        }
    }

    public array $regions {
        get {
            $ids = $this->__get('regions');
            if (!$ids) {
                return [];
            }

            return RegionModel::findMultipleByIds(explode(',', $this->__get('regions')))?->getModels() ?? [];
        }
    }

    public ?string $content {
        get {
            static $content = [];

            if (array_key_exists($this->id, $content)) {
                return $content[$this->id];
            }

            if (null === $collection = ContentModel::findPublishedByPidAndTable($this->id, self::$strTable)) {
                return $content[$this->id] = null;
            }

            $content[$this->id] = '';

            foreach ($collection as $contentModel) {
                $content[$this->id] .= Controller::getContentElement($contentModel->id);
            }

            return $content[$this->id];
        }
    }

    /**
     * @return self[]
     */
    public static function findAllPublished(array $options = []): array
    {

        return self::findAll(self::setPublishedOptions($options))?->getModels() ?? [];
    }

    private static function setPublishedOptions(array $options = []): array
    {
        if (!self::isPreviewMode($options)) {
            static $t = self::TABLE;
            static $time = Date::floorToMinute();

            $options['column'][] = "($t.start = '' OR $t.start <= $time)";
            $options['column'][] = "($t.stop = '' OR $t.stop > $time)";
            $options['column'][] = "($t.published = 1)";
        }

        return $options;
    }

}
