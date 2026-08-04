<?php

declare(strict_types=1);

namespace Respinar\CompanyBundle\Routing;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\Content\ContentUrlResolverInterface;
use Contao\CoreBundle\Routing\Content\ContentUrlResult;
use Contao\PageModel;
use Respinar\CompanyBundle\Model\TestimonialArchiveModel;
use Respinar\CompanyBundle\Model\TestimonialModel;

class TestimonialResolver implements ContentUrlResolverInterface
{
    public function __construct(private readonly ContaoFramework $framework)
    {
    }

    public function resolve(object $content): ContentUrlResult|null
    {
        if (!$content instanceof TestimonialModel) {
            return null;
        }

        $archiveAdapter = $this->framework->getAdapter(TestimonialArchiveModel::class);
        $pageAdapter = $this->framework->getAdapter(PageModel::class);

        $archive = $archiveAdapter->findById($content->pid);

        if (null === $archive || !$archive->jumpTo) {
            return null;
        }

        return ContentUrlResult::resolve(
            $pageAdapter->findPublishedById((int) $archive->jumpTo),
        );
    }

    public function getParametersForContent(object $content, PageModel $pageModel): array
    {
        if (!$content instanceof TestimonialModel) {
            return [];
        }

        return [
            'parameters' => '/'.($content->alias ?: $content->id),
        ];
    }
}
