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

$GLOBALS['TL_DCA']['tl_company_project'] =
[
    'config' => [
        'dataContainer' => DC_Table::class,
        'ptable' => 'tl_company_project_archive',
        'ctable' => ['tl_content'],
        'enableVersioning' => true,
        'switchToEdit' => true,
        'markAsCopy' => 'title',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'tstamp' => 'index',
                'alias' => 'index',
                'pid,published,featured,start,stop' => 'index',
            ],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_PARENT,
            'fields' => ['date DESC'],
            'headerFields' => ['title', 'jumpTo', 'tstamp', 'protected'],
            'panelLayout' => 'filter;sort,search,limit',
            'defaultSearchField' => 'title',
        ],
        'label' => [
            'fields' => ['title', 'date'],
            'format' => '%s <span class="label-info">[%s]</span>',
        ],
        'operations' => [
            'edit',
            'children',
            'copy',
            'cut',
            'delete',
            'toggle',
            'feature' => [
                'href' => 'act=toggle&field=featured',
                'icon' => 'featured.svg',
                'primary' => true,
            ],
            'show',
        ],
    ],
    'palettes' => [
        'default' => '{title_legend},title,featured,alias,author;{category_legend},categories;{date_legend},date,completionDate;{meta_legend},pageTitle,robots,description;{client_legend},client,location;{teaser_legend},summary;{image_legend},singleSRC;{publish_legend},published,start,stop',
    ],
    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
        ],
        'pid' => [
            'foreignKey' => 'tl_company_project_archive.title',
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
            'relation' => ['type' => 'belongsTo', 'load' => 'lazy'],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'title' => [
            'search' => true,
            'sorting' => true,
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'inputType' => 'text',
            'eval' => ['basicEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'alias' => [
            'search' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'alias', 'doNotCopy' => true, 'unique' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'save_callback' => [
                ['tl_company_project', 'generateAlias'],
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'platformOptions' => ['collation' => 'utf8mb4_bin']],
        ],
        'author' => [
            'default' => static fn () => BackendUser::getInstance()->id,
            'search' => true,
            'filter' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_user.name',
            'eval' => ['doNotCopy' => true, 'chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
            'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'date' => [
            'filter' => true,
            'default' => time(),
            'sorting' => true,
            'flag' => DataContainer::SORT_MONTH_BOTH,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'date', 'mandatory' => true, 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'completionDate' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'featured' => [
            'toggle' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'pageTitle' => [
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'robots' => [
            'search' => true,
            'backendSearch' => false,
            'inputType' => 'select',
            'options' => ['index,follow', 'index,nofollow', 'noindex,follow', 'noindex,nofollow'],
            'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true],
            'sql' => ['type' => 'string', 'length' => 32, 'default' => ''],
        ],
        'description' => [
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['style' => 'height:60px', 'tl_class' => 'clr'],
            'sql' => ['type' => 'text', 'length' => AbstractMySQLPlatform::LENGTH_LIMIT_TEXT, 'notnull' => false],
        ],
        'summary' => [
            'search' => true,
            'inputType' => 'textarea',
            'eval' => ['rte' => 'tinyMCE', 'basicEntities' => true, 'tl_class' => 'clr'],
            'sql' => ['type' => 'text', 'length' => AbstractMySQLPlatform::LENGTH_LIMIT_TEXT, 'notnull' => false],
        ],
        'singleSRC' => [
            'inputType' => 'fileTree',
            'eval' => ['fieldType' => 'radio', 'filesOnly' => true, 'extensions' => '%contao.image.valid_extensions%'],
            'sql' => ['type' => 'binary', 'length' => 16, 'fixed' => true, 'notnull' => false],
        ],
        'categories' => [
            'inputType' => 'picker',
            'foreignKey' => 'tl_company_category.title',
            'eval' => ['multiple' => true, 'tl_class' => 'clr'],
            'sql' => ['type' => 'blob', 'length' => 65535, 'notnull' => false],
            'relation' => ['type' => 'hasMany', 'load' => 'lazy'],
        ],
        'client' => [
            'inputType' => 'picker',
            'foreignKey' => 'tl_company_client.name',
            'eval' => ['tl_class' => 'w50'],
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
            'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'location' => [
            'inputType' => 'picker',
            'foreignKey' => 'tl_company_location.title',
            'eval' => ['tl_class' => 'clr'],
            'sql' => ['type' => 'blob', 'length' => 65535, 'notnull' => false],
            'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'published' => [
            'toggle' => true,
            'filter' => true,
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'start' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => ['type' => 'string', 'length' => 10, 'default' => ''],
        ],
        'stop' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => ['type' => 'string', 'length' => 10, 'default' => ''],
        ],
    ],
];

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @internal
 */
class tl_company_project extends Backend
{
    /**
     * Auto-generate the project alias if it has not been set yet.
     *
     * @throws Exception
     */
    public function generateAlias(string $varValue, DataContainer $dc): string
    {
        $aliasExists = static function (string $alias) use ($dc): bool {
            $result = Database::getInstance()
                ->prepare('SELECT id FROM tl_company_project WHERE alias=? AND id!=?')
                ->execute($alias, $dc->id)
            ;

            return $result->numRows > 0;
        };

        // Generate alias if there is none
        if (!$varValue) {
            $varValue = System::getContainer()->get('contao.slug')->generate(
                $dc->activeRecord->title,
                ProjectArchiveModel::findById($dc->activeRecord->pid)->jumpTo,
                $aliasExists,
            );
        } elseif (preg_match('/^[1-9]\d*$/', $varValue)) {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
        } elseif ($aliasExists($varValue)) {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
    }
}
