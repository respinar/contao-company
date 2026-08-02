<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Respinar\CompanyBundle\Model\ClientGroupModel;
use Respinar\CompanyBundle\Model\ClientModel;
use Respinar\CompanyBundle\Model\CompanyCategoryModel;
use Respinar\CompanyBundle\Model\CompanyLocationModel;
use Respinar\CompanyBundle\Model\ProjectArchiveModel;
use Respinar\CompanyBundle\Model\ProjectModel;
use Respinar\CompanyBundle\Model\TestimonialArchiveModel;
use Respinar\CompanyBundle\Model\TestimonialModel;

$GLOBALS['BE_MOD']['company'] = [
    'clients' => [
        'tables' => ['tl_company_client_group', 'tl_company_client'],
    ],
    'projects' => [
        'tables' => ['tl_company_project_archive', 'tl_company_project'],
    ],
    'testimonials' => [
        'tables' => ['tl_company_testimonial_archive', 'tl_company_testimonial'],
    ],
    'category' => [
        'tables' => ['tl_company_category'],
    ],
    'location' => [
        'tables' => ['tl_company_location'],
    ],
];

// Add permissions
$GLOBALS['TL_PERMISSIONS'][] = 'clients';
$GLOBALS['TL_PERMISSIONS'][] = 'projects';
$GLOBALS['TL_PERMISSIONS'][] = 'testimonials';

// Add models
$GLOBALS['TL_MODELS']['tl_company_category'] = CompanyCategoryModel::class;
$GLOBALS['TL_MODELS']['tl_company_location'] = CompanyLocationModel::class;
$GLOBALS['TL_MODELS']['tl_company_client_group'] = ClientGroupModel::class;
$GLOBALS['TL_MODELS']['tl_company_client'] = ClientModel::class;
$GLOBALS['TL_MODELS']['tl_company_project_archive'] = ProjectArchiveModel::class;
$GLOBALS['TL_MODELS']['tl_company_project'] = ProjectModel::class;
$GLOBALS['TL_MODELS']['tl_company_testimonial_archive'] = TestimonialArchiveModel::class;
$GLOBALS['TL_MODELS']['tl_company_testimonial'] = TestimonialModel::class;
