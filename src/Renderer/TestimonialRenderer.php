<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\Renderer;

use Contao\Config;
use Contao\ContentModel;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\Date;
use Contao\FrontendTemplate;
use Respinar\CompanyBundle\Model\ClientModel;
use Respinar\CompanyBundle\Model\ProjectModel;
use Respinar\CompanyBundle\Model\TestimonialModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestimonialRenderer
{
    public function __construct(
        private readonly Studio $studio,
        private readonly ContentUrlGenerator $contentUrlGenerator,
    ) {
    }

    public function renderTestimonial(TestimonialModel $testimonial, ContentModel $model): string
    {
        $template = new FrontendTemplate(
            $model->testimonial_template ?: 'testimonial_short',
        );

        $template->setData($testimonial->row());

        $class = 'testimonial';

        if (!empty($testimonial->featured)) {
            $class .= ' featured';
        }

        $template->class = trim($class);

        $template->dateFormatted = !empty($testimonial->date)
            ? Date::parse(Config::get('dateFormat'), (int) $testimonial->date)
            : '';

        if (!empty($testimonial->project)) {
            $project = ProjectModel::findById($testimonial->project);
            if (null !== $project) {
                $template->projectTitle = $project->title;

                $template->projectUrl = $this->contentUrlGenerator->generate($project, [], UrlGeneratorInterface::ABSOLUTE_PATH);
            }
        }

        if (!empty($testimonial->client)) {
            $client = ClientModel::findById($testimonial->client);
            if (null !== $client) {
                $template->clientName = $client->name;

                $template->clientUrl = $this->contentUrlGenerator->generate($client, [], UrlGeneratorInterface::ABSOLUTE_PATH);
            }
        }

        return $template->parse();
    }
}
