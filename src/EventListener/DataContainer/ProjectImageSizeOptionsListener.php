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

use Contao\BackendUser;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Image\ImageSizes;
use Symfony\Bundle\SecurityBundle\Security;

#[AsCallback('tl_content', 'fields.project_imgSize.options')]
class ProjectImageSizeOptionsListener
{
    public function __construct(
        private readonly Security $security,
        private readonly ImageSizes $imageSizes,
    ) {
    }

    public function __invoke(): array
    {
        $user = $this->security->getUser();

        if (!$user instanceof BackendUser) {
            return [];
        }

        return $this->imageSizes->getOptionsForUser($user);
    }
}
