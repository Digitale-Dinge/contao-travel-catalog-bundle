<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Controller;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\PageModel;
use Contao\StringUtil;
use DigitaleDinge\TravelCatalogBundle\Model\TravelRepository;
use DigitaleDinge\TravelCatalogBundle\Util\Pagination;
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

        $total = $this->travelRepository->countAllPublishedByCategories($categories);

        $pagination = new Pagination($total, $model->perPage, sprintf('page_%d', $model->id));

        if ($pagination->isOutOfRange()) {
            throw new PageNotFoundException('The pagination is out of range.');
        }

        $travels = $this->travelRepository->findAllPublishedByCategories($categories, $pagination->getCurrentPage(), $pagination->getPerPage());

        $pageModel = PageModel::findById($model->jumpTo);

        if ($pageModel instanceof PageModel) {
            foreach ($travels as &$travel) {
                $travel['href'] = $this->urlGenerator
                    ->generate($pageModel, [
                        'parameters' => '/' . $travel['alias'],
                        'travel_code' => $travel['travel_code']
                    ]);
            }
            unset($travel);
        }

        $template->set('travels', $travels);
        $template->set('pagination', $pagination->generate());

        return $template->getResponse();
    }

}
