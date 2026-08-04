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
use Respinar\CompanyBundle\Model\ClientModel;
use Respinar\CompanyBundle\Model\ProjectModel;
use Respinar\CompanyBundle\Renderer\ClientRenderer;
use Respinar\CompanyBundle\Renderer\ProjectRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('client_detail', category: 'company')]
class ClientDetailController extends AbstractContentElementController
{
    public function __construct(
        private readonly ClientRenderer $clientRenderer,
        private readonly ProjectRenderer $projectRenderer,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $alias = Input::get('auto_item');

        if (!$alias) {
            return new Response('Client Detail');
        }

        $client = ClientModel::findOneBy('alias', $alias);

        if (!$client) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $template->set('client', $this->clientRenderer->render($client, $model));

        // Find projects for this client
        $time = time();
        $projects = ProjectModel::findBy(
            ['client=?', 'published=?'],
            [$client->id, 1],
            ['order' => 'date DESC']
        );

        $projectsArr = [];

        $model->size = null;

        if ($projects !== null) {
            foreach ($projects as $project) {
                $projectsArr[] = $this->projectRenderer->render($project, $model);
            }
        }

        $template->set('projects', $projectsArr);

        return $template->getResponse();
    }
}
