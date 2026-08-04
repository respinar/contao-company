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
use Respinar\CompanyBundle\Renderer\ClientRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement('client_detail', category: 'company')]
class ClientDetailController extends AbstractContentElementController
{
    public function __construct(private readonly ClientRenderer $clientRenderer)
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $alias = Input::get('auto_item');

        if (!$alias) {
            return new Response('');
        }

        $client = ClientModel::findOneBy('alias', $alias);

        $template->set('clients', $this->clientRenderer->render($client, $model));

        return $template->getResponse();
    }
}
