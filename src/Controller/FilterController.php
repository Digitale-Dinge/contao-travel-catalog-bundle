<?php

declare(strict_types=1);

namespace DigitaleDinge\TravelCatalogBundle\Controller;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Intl\Countries;
use Contao\CoreBundle\Twig\FragmentTemplate;
use DigitaleDinge\TravelCatalogBundle\FormData\FilterData;
use DigitaleDinge\TravelCatalogBundle\Model\RegionRepository;
use DigitaleDinge\TravelCatalogBundle\Model\TravelRepository;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(self::TYPE, 'travel_catalog')]
final class FilterController extends AbstractContentElementController
{

    public const string TYPE = 'travel_catalog_filter';


    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly RegionRepository $regionRepository,
        private readonly TravelRepository $travelRepository,
        private readonly Countries $countries,
    )
    {
    }

    #[\Override]
    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $form = $this->getForm()->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->requestStack->getMainRequest()->attributes->set('tc_form', $form);
        }

        $template->set('form', $form->createView());

        return $template->getResponse();
    }

    private function getFormBuilder(): FormBuilderInterface
    {
        return $this->createFormBuilder(new FilterData(), [
            'method' => 'get',
            'csrf_protection' => false
        ])
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('oneDayTrip', CheckboxType::class, [
                'required' => false,
            ])
            ->add('date', DateType::class, [
                'required' => false,
            ])
            ->add('country', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choice_loader' => new CallbackChoiceLoader($this->getCountryChoices(...)),
            ])
            ->add('region', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choice_loader' => new CallbackChoiceLoader($this->getRegionChoices(...)),
            ])
            ->add('submit', SubmitType::class);
    }

    private function getForm(): FormInterface
    {
        return $this->getFormBuilder()->getForm();
    }

    private function getCountryChoices(): array
    {
        $countries = $this->countries->getCountries();

        $result = $this->travelRepository->findAllCountriesFromPublishedTravelsByCategories();

        return array_flip(array_intersect_key($countries, array_flip($result)));
    }

    private function getRegionChoices(): array
    {
        return array_column($this->regionRepository->findAllUsedByTravels(), 'id', 'name');
    }

}
