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
use Contao\Input;
use Contao\PageModel;
use Respinar\CompanyBundle\Model\ProjectArchiveModel;
use Respinar\CompanyBundle\Model\ProjectModel;
use Respinar\CompanyBundle\Renderer\ProjectRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('project_reader', category: 'company')]
class ProjectReaderController extends AbstractContentElementController
{
    public function __construct(private readonly ProjectRenderer $project_renderer)
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $alias = Input::get('auto_item');

        if (!$alias) {
            return new Response('');
        }

        $project = ProjectModel::findOneBy('alias', $alias);

        if (null === $project) {
            return new Response('');
        }

        $archive = ProjectArchiveModel::findById($project->pid);

        if (null === $archive) {
            return new Response('');
        }

        // Check publishing status and time window
        $time = time();
        if (!$project->published || ($project->start && $project->start > $time) || ($project->stop && $project->stop <= $time)) {
            return new Response('');
        }

        // Set page meta data
        $page = $GLOBALS['objPage'] ?? null;
        if ($page instanceof PageModel) {
            if ($project->pageTitle) {
                $page->pageTitle = $project->pageTitle;
            }
            if ($project->description) {
                $page->description = $project->description;
            }
            if ($project->robots) {
                $page->robots = $project->robots;
            }
        }

        $template->project = $this->project_renderer->render($project, $model);

        return $template->getResponse();
    }
}
