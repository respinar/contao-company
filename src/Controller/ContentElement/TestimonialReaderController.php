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
use Respinar\CompanyBundle\Repository\TestimonialRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('testimonial_reader', category: 'company')]
class TestimonialReaderController extends AbstractContentElementController
{
    public function __construct(
        private readonly TestimonialRepository $repository,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $alias = (string) ($request->attributes->get('auto_item') ?? $request->query->get('items'));

        $testimonial = $this->repository->findByAlias($alias);

        $template->testimonial = $testimonial;
        $template->model       = $model->row();

        return $template->getResponse();
    }
}
