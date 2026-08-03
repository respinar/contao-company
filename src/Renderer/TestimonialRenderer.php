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
use Contao\Date;
use Contao\FrontendTemplate;
use Respinar\CompanyBundle\Model\TestimonialModel;

final class TestimonialRenderer
{
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

        return $template->parse();
    }
}
