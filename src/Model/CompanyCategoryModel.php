<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\Model;

use Contao\Model;
use Contao\Model\Collection;

class CompanyCategoryModel extends Model
{
    protected static $strTable = 'tl_company_category';

    /**
     * Find all categories by parent ID.
     *
     * @param int   $intPid     The parent ID
     * @param array $arrOptions An optional options array
     *
     * @return array<static>|null
     */
    public static function findByPid(int $intPid, array $arrOptions = []): Collection|null
    {
        $t = static::$strTable;

        return static::findBy(["$t.pid=?"], [$intPid], $arrOptions);
    }

    /**
     * Get all root categories (categories with no parent).
     *
     * @param array $arrOptions An optional options array
     *
     * @return array<static>|null
     */
    public static function findRootCategories(array $arrOptions = []): Collection|null
    {
        $t = static::$strTable;

        return static::findBy(["$t.pid=?"], [0], $arrOptions);
    }
}
