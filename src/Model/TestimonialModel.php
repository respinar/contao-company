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
use Contao\StringUtil;

class TestimonialModel extends Model
{
    protected static $strTable = 'tl_company_testimonial';

    /**
     * Find published news items by their parent ID.
     *
     * @param int   $intId      The news archive ID
     * @param int   $intLimit   An optional limit
     * @param array $arrOptions An optional options array
     *
     * @return Collection<TestimonialModel>|null A collection of models or null if there are no news
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
     * Find published projects items by their parent ID.
     *
     * @param array<int>           $arrPids
     * @param array<string, mixed> $arrOptions
     *
     * @return Collection<TestimonialModel>|null
     */
    public static function findPublishedByPids(array $arrPids, bool|null $blnFeatured = null, array $arrCategoryIds = [], string $strOrder = 'date_desc', int $intLimit = 0, int $intOffset = 0, array $arrOptions = []): Collection|null
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
            $arrOptions['order'] = static::resolveOrder($strOrder);
        }

        $arrOptions['limit'] = $intLimit;
        $arrOptions['offset'] = $intOffset;

        $collection = static::findBy($arrColumns, null, $arrOptions);

        if (null === $collection || empty($arrCategoryIds)) {
            return $collection;
        }

        $models = [];

        foreach ($collection as $model) {
            $itemCategories = StringUtil::deserialize($model->categories ?? '', true);

            if (\count(array_intersect($itemCategories, $arrCategoryIds)) > 0) {
                $models[] = $model;
            }
        }

        if (empty($models)) {
            return null;
        }

        return new Collection($models, static::$strTable);
    }

    /**
     * Generates the label for a testimonial record.
     *
     * @param array<string, mixed> $row
     */
    public static function listTestimonials(array $row): string
    {
        return '<div class="tl_content_left">'.($row['title'] ?? '').'</div>';
    }

    private static function resolveOrder(string $order): string
    {
        $t = static::$strTable;

        return match ($order) {
            'date_asc' => "$t.date ASC",
            'date_desc' => "$t.date DESC",
            'random' => 'RAND()',
            default => "$t.date DESC",
        };
    }
}
