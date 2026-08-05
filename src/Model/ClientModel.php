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

use Contao\Date;
use Contao\Model;
use Contao\Model\Collection;

class ClientModel extends Model
{
    protected static $strTable = 'tl_company_client';

    /**
     * Find published news items by their parent ID.
     *
     * @param int   $intId      The news archive ID
     * @param int   $intLimit   An optional limit
     * @param array $arrOptions An optional options array
     *
     * @return Collection<ClientModel>|null A collection of models or null if there are no news
     */
    public static function findPublishedByPid(int $intId, int $intLimit = 0, array $arrOptions = []): Collection|null
    {
        $t = static::$strTable;
        $arrColumns = ["$t.pid=?"];

        if (!static::isPreviewMode($arrOptions)) {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published=1 AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
        }

        if (!isset($arrOptions['order'])) {
            $arrOptions['order'] = "$t.sorting ASC";
        }

        if ($intLimit > 0) {
            $arrOptions['limit'] = $intLimit;
        }

        return static::findBy($arrColumns, [$intId], $arrOptions);
    }


    /**
     * Find published product items by their parent ID and ID or alias.
     *
     * @param mixed $varId      The numeric ID or alias name
     * @param array $arrOptions An optional options array
     *
     * @return ClientModel|null The ClientModel or null if there are no client
     */
    public static function findPublishedByIdOrAlias(mixed $varId, array $arrOptions = []): ClientModel|null
    {
        $t = static::$strTable;
        $arrColumns = ["($t.id=? OR $t.alias=?)"];

        if (!static::isPreviewMode($arrOptions)) {
            $time = time();
            $arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
        }

        return static::findOneBy($arrColumns, [is_numeric($varId) ? $varId : 0, $varId], $arrOptions);
    }
}
