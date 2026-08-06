<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Respinar\CompanyBundle\Model\ProjectModel;
use Respinar\CompanyBundle\Renderer\ProjectRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('project_list', category: 'company')]
class ProjectListController extends AbstractContentElementController
{
    public function __construct(private readonly ProjectRenderer $projectRenderer)
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $archives = array_map('intval', (array) StringUtil::deserialize($model->project_archives, true));
        $archives = array_filter($archives);

        if (empty($archives)) {
            return $template->getResponse('');
        }

        $blnFeatured = match ($model->project_featured) {
            'featured' => true,
            'unfeatured' => false,
            default => null,
        };

        $arrOptions = [];

        if ('featured_first' === $model->project_featured) {
            $arrOptions['order'] = 'tl_company_project.featured DESC, tl_company_project.date ASC';
        }

        $limit = $model->numberOfItems > 0 ? (int) $model->numberOfItems : 0;

        $projectCollection = ProjectModel::findPublishedByPids($archives, $blnFeatured, $arrOptions, $limit);

        if (null === $projectCollection) {
            return $template->getResponse('');
        }

        // Preload all images in one query
        $projects = [];

        foreach ($projectCollection as $project) {
            $projects[] = $this->projectRenderer->render($project, $model);
        }

        $template->set('projects', $projects);

        return $template->getResponse();
    }
}
