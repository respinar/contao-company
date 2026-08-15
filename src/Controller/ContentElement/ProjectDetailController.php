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
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\Input;
use Contao\PageModel;
use Respinar\CompanyBundle\Model\ProjectArchiveModel;
use Respinar\CompanyBundle\Model\ProjectModel;
use Respinar\CompanyBundle\Model\TestimonialModel;
use Respinar\CompanyBundle\Renderer\ProjectRenderer;
use Respinar\CompanyBundle\Renderer\TestimonialRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('project_detail', category: 'company')]
class ProjectDetailController extends AbstractContentElementController
{
    public function __construct(
        private readonly ProjectRenderer $project_renderer,
        private readonly TestimonialRenderer $testimonial_renderer,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        if ('backend' === $request->attributes->get('_scope')) {
            $template->backend = true;
            $template->headline = $model->headline;

            return $template->getResponse();
        }

        $autoItem = Input::get('auto_item');

        // Return an empty string if "auto_item" is not set to combine list and reader on
        // same page
        if (null === $autoItem) {
            throw new PageNotFoundException('Id or alias not found: '.$request->getUri());
        }

        $project = ProjectModel::findPublishedByIdOrAlias($autoItem);

        if (null === $project) {
            throw new PageNotFoundException('Project not found: '.$request->getUri());
        }

        $archive = ProjectArchiveModel::findById($project->pid);

        if (null === $archive) {
            return new Response('');
        }

        $overviewPageUrl = null;
        if (!empty($archive->overviewPage)) {
            $overviewPage = PageModel::findById((int) $archive->overviewPage);
            if (null !== $overviewPage) {
                $overviewPageUrl = $overviewPage->getAbsoluteUrl();
            }
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
        $template->set('overviewPageUrl', $overviewPageUrl);

        // Fetch and render single testimonial for this project
        $testimonial = null;
        $testimonialObj = TestimonialModel::findPublishedByProject((int) $project->id);

        if (null !== $testimonialObj) {
            $testimonial = $this->testimonial_renderer->renderTestimonial($testimonialObj, $model);
        }

        $template->set('testimonial', $testimonial);

        return $template->getResponse();
    }
}
