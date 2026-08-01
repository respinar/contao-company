<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Respinar\CompanyBundle\Model\CompanyCategoryModel;
use Respinar\CompanyBundle\Model\CompanyLocationModel;

$GLOBALS['BE_MOD']['company']['category'] = [
    'tables' => ['tl_company_category'],
];

$GLOBALS['BE_MOD']['company']['location'] = [
    'tables' => ['tl_company_location'],
];

$GLOBALS['TL_MODELS']['tl_company_category'] = CompanyCategoryModel::class;
$GLOBALS['TL_MODELS']['tl_company_location'] = CompanyLocationModel::class;
