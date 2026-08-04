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
use Respinar\CompanyBundle\Model\TestimonialModel;
use Respinar\CompanyBundle\Renderer\TestimonialRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('testimonial_list', category: 'company')]
class TestimonialListController extends AbstractContentElementController
{
    public function __construct(private readonly TestimonialRenderer $renderer)
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $archives = StringUtil::deserialize($model->testimonial_archives, true);
        $categories = StringUtil::deserialize($model->testimonial_categories, true);

        $blnFeatured = match ($model->testimonial_featured) {
            'featured' => true,
            'unfeatured' => false,
            default => null,
        };

        $limit = $model->numberOfItems > 0 ? (int) $model->numberOfItems : 0;
        $order = $model->testimonial_order ?: 'date_desc';

        $testimonials = TestimonialModel::findPublishedByPids(
            $archives,
            $blnFeatured,
            $categories,
            $order,
            $limit,
        );

        if (null === $testimonials) {
            return $template->getResponse('');
        }

        $items = [];

        foreach ($testimonials as $testimonial) {
            $items[] = $this->renderer->renderTestimonial($testimonial, $model);
        }

        $template->set('testimonials', $items);

        return $template->getResponse();
    }
}
