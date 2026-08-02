<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Contao\System;
use Respinar\CompanyBundle\Repository\TestimonialRepository;
use Respinar\CompanyBundle\Renderer\TestimonialRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('testimonial_list', category: 'company')]
class TestimonialListController extends AbstractContentElementController
{
    public function __construct(
        private readonly TestimonialRepository $repository,
        private readonly TestimonialRenderer $renderer,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $archives   = StringUtil::deserialize($model->testimonial_archives, true);
        $categories = StringUtil::deserialize($model->testimonial_categories, true);
        $featured   = $model->testimonial_featured;
        $limit      = $model->numberOfItems > 0 ? (int) $model->numberOfItems : null;
        $order      = $model->testimonial_order;

        $testimonials = $this->repository->findPublished($archives, $categories, $featured, $order, $limit);

        if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))) {
            $model->testimonial_template = 'testimonial_short';
        }

        $template->cards = array_map(
            fn (array $t) => $this->renderer->renderTestimonial($t, $model),
            $testimonials,
        );

        return $template->getResponse();
    }
}
