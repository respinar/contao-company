<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Contao\Backend;
use Contao\BackendUser;
use Contao\Database;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\System;
use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Respinar\CompanyBundle\Model\ProjectArchiveModel;

$GLOBALS['TL_DCA']['tl_company_project'] = array
(
    'config' => array
    (
        'dataContainer'               => DC_Table::class,
        'ptable'                      => 'tl_company_project_archive',
        'ctable'                      => array('tl_content'),
        'enableVersioning'            => true,
        'switchToEdit'                => true,
        'markAsCopy'                  => 'title',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'tstamp' => 'index',
                'alias' => 'index',
                'pid,published,featured,start,stop' => 'index'
            )
        )
    ),
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => DataContainer::MODE_PARENT,
            'fields'                  => array('date DESC'),
            'headerFields'            => array('title', 'jumpTo', 'tstamp', 'protected'),
            'panelLayout'             => 'filter;sort,search,limit',
            'defaultSearchField'      => 'title'
        ),
        'label' => array
        (
            'fields'                  => array('title', 'date'),
            'format'                  => '%s <span class="label-info">[%s]</span>',
        ),
        'operations' => array
        (
            'edit',
            'children',
            'copy',
            'cut',
            'delete',
            'toggle' => array
            (
                'href'                => 'act=toggle&field=published',
                'icon'                => 'visible.svg',
                'primary'             => true,
                'showInHeader'        => true
            ),
            'feature' => array
            (
                'href'                => 'act=toggle&field=featured',
                'icon'                => 'featured.svg',
                'primary'             => true,
            ),
            'show',
            'versions',
        )
    ),
    'palettes' => array
    (
        'default'                     => '{title_legend},title,featured,alias,author;{category_legend},categories;{date_legend},date,completionDate;{meta_legend},pageTitle,robots,description;{client_legend},client,location;{teaser_legend},summary;{image_legend},singleSRC;{publish_legend},published,start,stop'
    ),
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => array('type'=>'integer', 'unsigned'=>true, 'autoincrement'=>true)
        ),
        'pid' => array
        (
            'foreignKey'              => 'tl_company_project_archive.title',
            'sql'                     => array('type'=>'integer', 'unsigned'=>true, 'default'=>0),
            'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'tstamp' => array
        (
            'sql'                     => array('type'=>'integer', 'unsigned'=>true, 'default'=>0)
        ),
        'title' => array
        (
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
            'inputType'               => 'text',
            'eval'                    => array('basicEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => array('type'=>'string', 'length'=>255, 'default'=>'')
        ),
        'alias' => array
        (
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'unique'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'save_callback' => array
            (
                array('tl_company_project', 'generateAlias')
            ),
            'sql'                     => array('type'=>'string', 'length'=>255, 'default'=>'', 'platformOptions'=>array('collation'=>'utf8mb4_bin'))
        ),
        'author' => array
        (
            'default'                 => static fn () => BackendUser::getInstance()->id,
            'search'                  => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_user.name',
            'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => array('type'=>'integer', 'unsigned'=>true, 'default'=>0),
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ),
        'date' => array
        (
            'filter'                  => true,
            'default'                 => time(),
            'sorting'                 => true,
            'flag'                    => DataContainer::SORT_MONTH_BOTH,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'date', 'mandatory'=>true, 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => array('type'=>'integer', 'unsigned'=>true, 'default'=>0)
        ),
        'completionDate' => array
        (
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => array('type'=>'integer', 'unsigned'=>true, 'default'=>0)
        ),
        'featured' => array
        (
            'toggle'                  => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => array('type'=>'boolean', 'default'=>false)
        ),
        'pageTitle' => array
        (
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => array('type'=>'string', 'length'=>255, 'default'=>'')
        ),
        'robots' => array
        (
            'search'                  => true,
            'backendSearch'           => false,
            'inputType'               => 'select',
            'options'                 => array('index,follow', 'index,nofollow', 'noindex,follow', 'noindex,nofollow'),
            'eval'                    => array('tl_class'=>'w50', 'includeBlankOption'=>true),
            'sql'                     => array('type'=>'string', 'length'=>32, 'default'=>'')
        ),
        'description' => array
        (
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('style'=>'height:60px', 'tl_class'=>'clr'),
            'sql'                     => array('type'=>'text', 'length'=>AbstractMySQLPlatform::LENGTH_LIMIT_TEXT, 'notnull'=>false)
        ),
        'summary' => array
        (
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('rte'=>'tinyMCE', 'basicEntities'=>true, 'tl_class'=>'clr'),
            'sql'                     => array('type'=>'text', 'length'=>AbstractMySQLPlatform::LENGTH_LIMIT_TEXT, 'notnull'=>false)
        ),
        'singleSRC' => array
        (
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'extensions'=>'%contao.image.valid_extensions%'),
            'sql'                     => array('type'=>'binary', 'length'=>16, 'fixed'=>true, 'notnull'=>false)
        ),
        'categories' => array
        (
            'inputType'               => 'picker',
            'foreignKey'              => 'tl_company_category.title',
            'eval'                    => array('multiple'=>true, 'tl_class'=>'clr'),
            'sql'                     => array('type'=>'blob', 'length'=>65535, 'notnull'=>false),
            'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
        ),
        'client' => array
        (
            'inputType'               => 'picker',
            'foreignKey'              => 'tl_company_client.name',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => array('type'=>'integer', 'unsigned'=>true, 'default'=>0),
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ),
        'location' => array
        (
            'inputType'               => 'picker',
            'foreignKey'              => 'tl_company_location.title',
            'eval'                    => array('tl_class'=>'clr'),
            'sql'                     => array('type'=>'blob', 'length'=>65535, 'notnull'=>false),
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ),
        'published' => array
        (
            'toggle'                  => true,
            'filter'                  => true,
            'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true),
            'sql'                     => array('type'=>'boolean', 'default'=>false)
        ),
        'start' => array
        (
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => array('type'=>'string', 'length'=>10, 'default'=>'')
        ),
        'stop' => array
        (
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => array('type'=>'string', 'length'=>10, 'default'=>'')
        )
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @internal
 */
class tl_company_project extends Backend
{
    /**
     * Auto-generate the project alias if it has not been set yet
     *
     * @throws Exception
     */
    public function generateAlias(string $varValue, DataContainer $dc): string
    {
        $aliasExists = static function (string $alias) use ($dc): bool {
            $result = Database::getInstance()
                ->prepare("SELECT id FROM tl_company_project WHERE alias=? AND id!=?")
                ->execute($alias, $dc->id);

            return $result->numRows > 0;
        };

        // Generate alias if there is none
        if (!$varValue)
        {
            $varValue = System::getContainer()->get('contao.slug')->generate(
                $dc->activeRecord->title,
                ProjectArchiveModel::findById($dc->activeRecord->pid)->jumpTo,
                $aliasExists
            );
        }
        elseif (preg_match('/^[1-9]\d*$/', $varValue))
        {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
        }
        elseif ($aliasExists($varValue))
        {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
    }
}
