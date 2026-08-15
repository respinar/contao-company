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

use Contao\Date;
use Contao\Model;
use Contao\Model\Collection;

/**
 * Reads and writes projects.
 *
 * @property int    $id
 * @property int    $pid
 * @property int    $tstamp
 * @property string $title
 * @property string $alias
 * @property int    $author
 * @property int    $date
 * @property int    $completionDate
 * @property bool   $featured
 * @property string $summary
 * @property string $description
 * @property string $singleSRC
 * @property string $multiSRC
 * @property bool   $published
 * @property string $start
 * @property string $stop
 *
 * @method static ProjectModel|null             findById($id, array $opt=array())
 * @method static ProjectModel|null             findByPk($id, array $opt=array())
 * @method static ProjectModel|null             findOneBy($col, $val, array $opt=array())
 * @method static Collection<ProjectModel>|null findBy($col, $val, array $opt=array())
 * @method static Collection<ProjectModel>|null findAll(array $opt=array())
 */
class ProjectModel extends Model
{
    protected static $strTable = 'tl_company_project';

    /**
     * Find published projects items by their parent ID.
     *
     * @param array<int>           $arrPids
     * @param array<string, mixed> $arrOptions
     *
     * @return Collection<ProjectModel>|null
     */
    public static function findPublishedByPids(array $arrPids, bool|null $blnFeatured = null, array $arrOptions = []): Collection|null
    {
        if (empty($arrPids) || !\is_array($arrPids)) {
            return null;
        }

        $t = static::$strTable;
        $arrColumns = ["$t.pid IN(".implode(',', array_map('\intval', $arrPids)).')'];

        if (true === $blnFeatured) {
            $arrColumns[] = "$t.featured=1";
        } elseif (false === $blnFeatured) {
            $arrColumns[] = "$t.featured=0";
        }

        if (!static::isPreviewMode($arrOptions)) {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published=1 AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
        }

        if (!isset($arrOptions['order'])) {
            $arrOptions['order'] = "$t.date DESC";
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

    /**
     * Find published project items by their parent ID and ID or alias.
     *
     * @param mixed $varId      The numeric ID or alias name
     * @param array $arrPids    An array of parent IDs
     * @param array $arrOptions An optional options array
     *
     * @return ProjectModel|null The productModel or null if there are no product
     */
    public static function findPublishedByIdOrAlias($varId, array $arrOptions = []): ?ProjectModel
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
