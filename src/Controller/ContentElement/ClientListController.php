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
use Respinar\CompanyBundle\Model\ClientModel;
use Respinar\CompanyBundle\Renderer\ClientRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('client_list', category: 'company')]
class ClientListController extends AbstractContentElementController
{
    public function __construct(private readonly ClientRenderer $clientRenderer)
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $clients = [];

        if (empty($model->clientGroup)) {
            return $template->getResponse('');
        }

        $clientCollection = ClientModel::findPublishedByPid($model->clientGroup, $model->numberOfItems);

        if (null !== $clientCollection) {
            foreach ($clientCollection as $client) {
                $clients[] = $this->clientRenderer->render($client, $model);
            }
        }

        $template->set('clients', $clients);

        return $template->getResponse();
    }
}
