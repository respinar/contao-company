<?php

declare(strict_types=1);

namespace Respinar\CompanyBundle\Routing;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\Content\ContentUrlResolverInterface;
use Contao\CoreBundle\Routing\Content\ContentUrlResult;
use Contao\PageModel;
use Respinar\CompanyBundle\Model\ClientGroupModel;
use Respinar\CompanyBundle\Model\ClientModel;

class ClientResolver implements ContentUrlResolverInterface
{
    public function __construct(private readonly ContaoFramework $framework)
    {
    }

    public function resolve(object $content): ContentUrlResult|null
    {
        if (!$content instanceof ClientModel) {
            return null;
        }

        $groupAdapter = $this->framework->getAdapter(ClientGroupModel::class);
        $pageAdapter = $this->framework->getAdapter(PageModel::class);

        $group = $groupAdapter->findById($content->pid);

        if (null === $group || !$group->jumpTo) {
            return null;
        }

        return ContentUrlResult::resolve(
            $pageAdapter->findPublishedById((int) $group->jumpTo),
        );
    }

    public function getParametersForContent(object $content, PageModel $pageModel): array
    {
        if (!$content instanceof ClientModel) {
            return [];
        }

        return [
            'parameters' => '/'.($content->alias ?: $content->id),
        ];
    }
}
