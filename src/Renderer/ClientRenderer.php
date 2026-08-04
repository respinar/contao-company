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
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Respinar\CompanyBundle\Model\ClientModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ClientRenderer
{
    public function __construct(
        private readonly Studio $studio,
        private readonly ContentUrlGenerator $contentUrlGenerator,
    ) {
    }

    /**
     * Parse a product.
     */
    public function render(ClientModel $client, ContentModel $model): string
    {
        $template = new FrontendTemplate(
            $model->client_template ?: 'client_short',
        );

        $template->setData($client->row());

        $template->addImage = false;

        if ($client->logo) {
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
                ->from($client->logo)
                ->build()
            ;

            $template->figure = $figure;
        }

        $template->link = $this->contentUrlGenerator->generate($client, [], UrlGeneratorInterface::ABSOLUTE_PATH);

        return $template->parse();
    }
}
