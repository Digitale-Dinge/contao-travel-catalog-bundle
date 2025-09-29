<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Controller;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\PageModel;
use Contao\StringUtil;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;
use DigitaleDinge\TravelCatalogBundle\Model\TravelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(self::TYPE, 'travel_catalog')]
final class ListController extends AbstractContentElementController
{

    public const string TYPE = 'travel_catalog_list';

    public function __construct(
        private readonly TravelRepository $travelRepository,
        private readonly ContentUrlGenerator $urlGenerator
    )
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $categories = StringUtil::deserialize($model->tc_categories, true);


        if (count($categories) > 0) {
            $travels = DateModel::findPublishedByCategories($categories, [
                'order' => DateModel::fqid('departure', 'ASC'),
            ]);
        } else {
            $travels = DateModel::findAllPublished([
                'order' => DateModel::fqid('departure', 'ASC'),
                'eager' => true
            ]);
        }

        $pageModel = PageModel::findById($model->jumpTo);

        foreach ($travels as $travel) {
            if ($pageModel instanceof PageModel) {
                $href = $this->urlGenerator->generate($pageModel, ['parameters' => '/' . $travel->alias, 'travel_code' => $travel->travel_code]);
                $travel->href = $href;
            }
        }

        $template->set('travels', $travels);

        return $template->getResponse();
    }

}
