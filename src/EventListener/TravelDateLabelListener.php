<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\EventListener;

use Contao\Config;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\Date;
use Contao\StringUtil;

#[AsCallback(table: 'tc_date', target: 'list.label.label')]
class TravelDateLabelListener
{
    public function __invoke(array $row, string $label, DataContainer $dc, array $labels): array
    {
        $fields = $GLOBALS['TL_DCA'][$dc->table]['list']['label']['fields'] ?? [];

        foreach ($fields as $i => $field) {
            if (in_array($field, ['departure', 'return'], true) && !empty($row[$field])) {
                $labels[$i] = Date::parse(Config::get('dateFormat'), (int) $row[$field]);
            }

            if ($field === 'prices' && !empty($row['prices'])) {
                $prices = StringUtil::deserialize($row['prices'], true);

                $labels[$i] = implode('<br>', array_map(
                    fn($price) => "{$price['description']}: <span class='tl_green'>{$price['price']}€</span> (<s class='tl_red'>{$price['old_price']} €</s>)",
                    $prices
                ));
            }
        }

        return $labels;
    }
}
