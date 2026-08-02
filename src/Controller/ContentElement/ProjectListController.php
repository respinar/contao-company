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
    public function __construct(
        private readonly ProjectRenderer $project_renderer,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $archives = array_map('intval', (array) StringUtil::deserialize($model->project_archives, true));
        $archives = array_filter($archives);

        if (empty($archives)) {
            return new Response('');
        }

        $time = time();
        $columns = array(
            'pid IN (' . implode(',', array_fill(0, count($archives), '?')) . ')',
            'published=1',
            "(start='' OR start<=$time)",
            "(stop='' OR stop>$time)"
        );

        $values = $archives;

        $projectCollection = ProjectModel::findBy($columns, $values, array('order' => 'date DESC'));

        if ($projectCollection === null) {
            return new Response('');
        }

        // Preload all images in one query
        $projects = [];

        foreach ($projectCollection as $project) {
            $projects[] = $this->project_renderer->render($project, $model);
        }

        $template->set('projects', $projects);

        return $template->getResponse();
    }
}
