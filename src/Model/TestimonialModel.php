<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\Model;

use Contao\Model;

class TestimonialModel extends Model
{
    protected static $strTable = 'tl_company_testimonial';

    /**
     * Generates the label for a testimonial record.
     *
     * @param array<string, mixed> $row
     */
    public static function listTestimonials(array $row): string
    {
        return '<div class="tl_content_left">' . ($row['title'] ?? '') . '</div>';
    }
}
