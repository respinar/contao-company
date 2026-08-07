<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company ‌‌Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Contao\DataContainer;
use Contao\DC_Table;
use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;

$GLOBALS['TL_DCA']['tl_company_testimonial'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'ptable' => 'tl_company_testimonial_archive',
        'enableVersioning' => true,
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
            'panelLayout' => 'filter;sorting,search,limit',
        ],
        'label' => [
            'fields' => ['title', 'date'],
            'format' => '%s <span class="label-info">[%s]</span>',
        ],
        'operations' => [
            'edit',
            'copy',
            'delete',
            'toggle' => [
                'href' => 'act=toggle&field=published',
                'icon' => 'toggle.svg',
                'primary' => true,
                'showInHeader' => true,
            ],
            'feature' => [
                'href' => 'act=toggle&field=featured',
                'icon' => 'featured.svg',
                'primary' => true,
            ],
            'show',
        ],
    ],

    'palettes' => [
        'default' => '{title_legend},title,featured,alias,date;{person_legend},client,personName,position;{categories_legend},categories;{relation_legend},project;{content_legend},quote;{media_legend},letterImage,pdf;{publish_legend},published,start,stop',
    ],

    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
        ],
        'pid' => [
            'foreignKey' => 'tl_company_testimonial_archive.title',
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
            'relation' => ['type' => 'belongsTo', 'load' => 'lazy'],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'title' => [
            'inputType' => 'text',
            'search' => true,
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'alias' => [
            'inputType' => 'text',
            'search' => true,
            'eval' => ['rgxp' => 'alias', 'doNotCopy' => true, 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 128, 'default' => ''],
        ],
        'personName' => [
            'inputType' => 'text',
            'search' => true,
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50 clr'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'position' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'quote' => [
            'inputType' => 'textarea',
            'eval' => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql' => ['type' => 'text', 'length' => AbstractMySQLPlatform::LENGTH_LIMIT_TEXT, 'notnull' => false],
        ],
        'letterImage' => [
            'inputType' => 'fileTree',
            'eval' => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => '%contao.image.valid_extensions%', 'tl_class' => 'clr'],
            'sql' => ['type' => 'binary', 'length' => 16, 'notnull' => false],
        ],
        'pdf' => [
            'inputType' => 'fileTree',
            'eval' => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'pdf', 'tl_class' => 'clr'],
            'sql' => ['type' => 'binary', 'length' => 16, 'notnull' => false],
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
            'eval' => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
            'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'project' => [
            'inputType' => 'picker',
            'foreignKey' => 'tl_company_project.title',
            'eval' => ['tl_class' => 'w50'],
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
            'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'date' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => ['type' => 'string', 'length' => 10, 'default' => ''],
        ],
        'featured' => [
            'toggle' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12'],
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
        'published' => [
            'toggle' => true,
            'filter' => true,
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
