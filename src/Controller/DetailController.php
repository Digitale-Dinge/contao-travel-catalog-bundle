<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Controller;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Routing\ResponseContext\ResponseContextAccessor;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\Input;
use DigitaleDinge\TravelCatalogBundle\Model\DateModel;
use DigitaleDinge\TravelCatalogBundle\Model\TravelModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tastaturberuf\ContaoAnystoresBundle\Event\DetailControllerEvent;

#[AsContentElement(self::TYPE, 'travel_catalog')]
final class DetailController extends AbstractContentElementController
{

    public const string TYPE = 'travel_catalog_detail';


    public function __construct(private readonly ResponseContextAccessor $responseContextAccessor)
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $travel = TravelModel::findByIdOrAlias(Input::get('auto_item'));

        if ($travel === null) {
            throw $this->createNotFoundException('The travel does not exist.');
        }

        $this->setHtmlMetaData($travel);

        $prices = DateModel::findPublishedByPid($travel->id, [
            'order' => DateModel::fqid('departure', 'ASC')
        ]);

        $template->set('travel', $travel);
        $template->set('prices', $prices);

        return $template->getResponse();
    }

    private function setHtmlMetaData(TravelModel $travel): void
    {
        $responseContext = $this->responseContextAccessor->getResponseContext();

        if (null === $responseContext || !$responseContext->has(HtmlHeadBag::class)) {
            return;
        }

        $htmlHeadBag = $responseContext->get(HtmlHeadBag::class);

        $htmlHeadBag->setTitle($travel->metaTitle ?? $travel->name);
        $htmlHeadBag->setMetaDescription($travel->metaDescription ?? $travel->description);
    }

}
