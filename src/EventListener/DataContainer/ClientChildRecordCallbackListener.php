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
use Contao\FilesModel;
use Contao\Image;

#[AsCallback(table: 'tl_company_client', target: 'list.sorting.child_record')]
class ClientChildRecordCallbackListener
{
    /**
     * @param array<string, mixed> $row
     */
    public function __invoke(array $row): string
    {
        $logo = '';

        if (!empty($row['logo'])) {
            $fileModel = FilesModel::findByUuid($row['logo']);

            if (null !== $fileModel) {
                $logo = Image::getHtml(
                    $fileModel->path,
                    '',
                    'style="max-width:40px; max-height:40px; margin-right:8px; vertical-align:middle; object-fit:contain;"',
                );
            }
        }

        return \sprintf(
            '<div class="tl_content_left">%s%s</div>',
            $logo,
            htmlspecialchars($row['name'] ?? ''),
        );
    }
}
