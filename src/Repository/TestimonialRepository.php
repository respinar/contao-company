<?php

declare(strict_types=1);

/*
 * This file is part of Contao Testimonials.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\Repository;

use Contao\StringUtil;
use Respinar\CompanyBundle\Model\TestimonialModel;

class TestimonialRepository
{
    /**
     * Finds published records.
     *
     * @param list<int>    $archiveIds
     * @param list<int>    $categoryIds
     * @param 'all'|'featured'|'unfeatured' $featured
     * @param string       $order
     * @param int|null     $limit
     *
     * @return array<int, array<string, mixed>>
     */
    public function findPublished(array $archiveIds = [], array $categoryIds = [], string $featured = 'all', string $order = 'sorting_asc', ?int $limit = null): array
    {
        $options = [
            'column' => ['published = ?'],
            'value'  => ['1'],
            'order'  => $this->resolveOrder($order),
        ];

        if (!empty($archiveIds)) {
            $placeholders = implode(',', array_fill(0, count($archiveIds), '?'));
            $options['column'][] = "pid IN ($placeholders)";
            array_push($options['value'], ...$archiveIds);
        }

        if ($featured !== 'all') {
            $options['column'][] = 'featured = ?';
            $options['value'][] = $featured === 'featured' ? '1' : '';
        }

        if ($limit !== null && $limit > 0) {
            $options['limit'] = $limit;
        }

        $collection = TestimonialModel::findAll($options);

        $items = [];
        if ($collection !== null) {
            while ($collection->next()) {
                $items[] = $collection->current()->row();
            }
        }

        if (!empty($categoryIds)) {
            $items = array_values(array_filter(
                $items,
                static function (array $item) use ($categoryIds): bool {
                    $itemCategories = StringUtil::deserialize($item['categories'] ?? '', true);

                    return count(array_intersect($itemCategories, $categoryIds)) > 0;
                }
            ));
        }

        return $items;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByAlias(string $alias): ?array
    {
        if ($alias === '') {
            return null;
        }

        $testimonial = TestimonialModel::findOneBy('alias', $alias);

        if ($testimonial === null || !$testimonial->published) {
            return null;
        }

        return $testimonial->row();
    }

    private function resolveOrder(string $order): string
    {
        return match ($order) {
            'sorting_desc' => 'sorting DESC',
            'date_asc'     => 'date ASC',
            'date_desc'    => 'date DESC',
            'random'       => 'RAND()',
            default        => 'sorting ASC',
        };
    }
}
