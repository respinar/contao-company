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

class CompanyLocationModel extends Model
{
    protected static $strTable = 'tl_company_location';

    /**
     * Find all categories by parent ID.
     *
     * @param int   $intPid    The parent ID
     * @param array $arrOptions An optional options array
     *
     * @return static[]|null
     */
    public static function findByPid(int $intPid, array $arrOptions = []): ?\Contao\Model\Collection
    {
        $t = static::$strTable;

        return static::findBy(["$t.pid=?"], [$intPid], $arrOptions);
    }

    /**
     * Get all root categories (categories with no parent).
     *
     * @param array $arrOptions An optional options array
     *
     * @return static[]|null
     */
    public static function findRootCategories(array $arrOptions = []): ?\Contao\Model\Collection
    {
        $t = static::$strTable;

        return static::findBy(["$t.pid=?"], [0], $arrOptions);
    }
}
