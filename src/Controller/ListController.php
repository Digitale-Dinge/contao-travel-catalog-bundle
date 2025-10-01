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
use DigitaleDinge\TravelCatalogBundle\FormData\FilterData;
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

    #[\Override]
    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $filterData = $this->getFormData($request, $model);

        $template->set('travels', $this->getTravels($filterData, $model));
        $template->set('total', $filterData->limit);
        $template->set('pagination', $this->getPagination($filterData, $model->id)->generate());

        return $template->getResponse();
    }

    private function getFormData(Request $request, ContentModel $contentModel): FilterData
    {
        $filterData = $request->attributes->get('tc_form')?->getData() ?? new FilterData;

        $filterData->categories = StringUtil::deserialize($contentModel->tc_categories, true);
        $filterData->perPage = $contentModel->perPage ?? 0;
        $filterData->limit = $contentModel->numberOfItems ?: $this->travelRepository->countAllPublished($filterData);

        return $filterData;
    }

    private function getPagination(FilterData $filterData, int $modelId): Pagination
    {
        $pagination = new Pagination(
            $filterData->limit,
            $filterData->perPage,
            sprintf('page_%d', $modelId)
        );

        if ($pagination->isOutOfRange()) {
            throw new PageNotFoundException('The pagination is out of range.');
        }

        return $pagination;
    }

    private function getTravels(FilterData $filterData, ContentModel $contentModel): array
    {
        $travels = $this->travelRepository->findAllPublished($filterData);

        $pageModel = PageModel::findById($contentModel->jumpTo);

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

        return $travels;
    }

}
