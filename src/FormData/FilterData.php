<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\FormData;

class FilterData
{

    public ?string $name = null;
    public ?\DateTimeInterface $date = null;
    public array $country = [];
    public array $region = [];

    public array $categories = [];
    public int $page = 1;
    public int $perPage = 0;
    public int $limit = 0;

}
