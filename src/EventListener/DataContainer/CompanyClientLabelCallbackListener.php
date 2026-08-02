<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\Image;
use Contao\StringUtil;

#[AsCallback('tl_company_client', 'list.sorting.child_record_callback')]
class CompanyClientLabelCallbackListener
{
    /**
     * @param array<string, mixed> $row
     */
    public function __invoke(array $row): string
    {
        $label = '<strong>'.$row['name'].'</strong>';

        if ($row['logo']) {
            $uuid = StringUtil::binToUuid($row['logo']);
            $label = Image::getHtml($uuid, '', 'style="max-height:32px;max-width:50px;vertical-align:middle;margin-right:8px;"').' '.$label;
        }

        if ($row['industry']) {
            $label .= ' <span style="color:#999;padding-left:3px">['.$row['industry'].']</span>';
        }

        return $label;
    }
}
