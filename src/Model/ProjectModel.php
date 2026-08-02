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
    public static function findPublishedByPids(array $arrPids, bool|null $blnFeatured = null, int $intLimit = 0, int $intOffset = 0, array $arrOptions = []): Collection|null
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

        $arrOptions['limit'] = $intLimit;
        $arrOptions['offset'] = $intOffset;

        return static::findBy($arrColumns, null, $arrOptions);
    }
}
