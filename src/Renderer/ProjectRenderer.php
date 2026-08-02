<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\Renderer;

use Contao\ContentModel;
use Contao\Controller;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Respinar\CompanyBundle\Model\ProjectModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


final class ProjectRenderer
{
    public function __construct(
        private readonly Studio $studio,
        private readonly ContentUrlGenerator $contentUrlGenerator,
    ) {
    }

    /**
     * Parse a product.
     */
    public function render(ProjectModel $project, ContentModel $model): string
    {
        $template = new FrontendTemplate(
            $model->project_template ?: 'project_card',
        );

        $template->setData($project->row());

        $class = '';

        if (time() - $project->date < 2592000) {
            $template->new_product = true;
            $class .= ' new';
        }

        if ($project->featured) {
            $class .= ' featured';
        }

        if ($project->cssClass) {
            $class .= ' '.$project->cssClass;
        }

        $template->class = trim($class);


        $template->hasText = false;

        $elements = ContentModel::findPublishedByPidAndTable($project->id, 'tl_company_project');

        if (null !== $elements) {
            $template->hasText = true;

            while ($elements->next()) {
                $template->text .= Controller::getContentElement(
                    $elements->current(),
                );
            }

            $template->link = $this->contentUrlGenerator->generate($project, [], UrlGeneratorInterface::ABSOLUTE_PATH);
        }

        $template->addImage = false;

        if ($project->singleSRC) {
            $size = null;

            if ($model->size) {
                $imgSize = StringUtil::deserialize($model->size);

                if (
                    $imgSize[0] > 0
                    || $imgSize[1] > 0
                    || is_numeric($imgSize[2])
                    || ($imgSize[2][0] ?? null) === '_'
                ) {
                    $size = $model->size;
                }
            }

            $figure = $this->studio
                ->createFigureBuilder()
                ->setSize($size)
                ->from($project->singleSRC)
                ->build()
            ;

            $template->figure = $figure;
        }

        return $template->parse();
    }
}
