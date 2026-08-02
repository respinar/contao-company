<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company ‌‌Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Contao\DC_Table;
use Contao\DataContainer;

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
                'pid,published,featured,start,stop' => 'index'
            ],
        ],
    ],

    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_PARENT,
            'fields' => ['date DESC'],
            'headerFields' => ['title', 'jumpTo', 'tstamp', 'protected'],
            'panelLayout' => 'filter;sorting,search,limit',
            // 'child_record_callback' => ['Respinar\CompanyBundle\Model\TestimonialModel', 'listTestimonials'],
        ],
        'label' => array
        (
            'fields'                  => array('title', 'date'),
            'format'                  => '%s <span class="label-info">[%s]</span>',
        ),
        'operations' => [
            'edit' ,
            'copy',
            'delete',
            'toggle',
            'feature' => array
            (
                'href'                => 'act=toggle&field=featured',
                'icon'                => 'featured.svg',
                'primary'             => true,
            ),
            'show',
        ],
    ],

    'palettes' => [
        'default' => '{title_legend},title,featured,alias,date;{person_legend},client,personName,position;{categories_legend},categories;{relation_legend},project;{content_legend},quote;{media_legend},letterImage,pdf;{publish_legend},published,sorting',
    ],

    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'pid' => [
            'foreignKey' => 'tl_company_testimonial_archive.title',
            'sql' => "int(10) unsigned NOT NULL default 0",
            'relation' => ['type' => 'belongsTo', 'load' => 'lazy'],
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default 0",
        ],
        'title' => [
            'inputType' => 'text',
            'search' => true,
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'alias' => [
            'inputType' => 'text',
            'search' => true,
            'eval' => ['rgxp' => 'alias', 'doNotCopy' => true, 'unique' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'sql' => "varchar(128) NOT NULL default ''",
        ],
        'personName' => [
            'inputType' => 'text',
            'search' => true,
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50 clr'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'position' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'quote' => [
            'inputType' => 'textarea',
            'eval' => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql' => 'text NULL',
        ],
        'letterImage' => [
            'inputType' => 'fileTree',
            'eval' => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png,gif,svg', 'tl_class' => 'clr'],
            'sql' => 'binary(16) NULL',
        ],
        'pdf' => [
            'inputType' => 'fileTree',
            'eval' => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'pdf', 'tl_class' => 'clr'],
            'sql' => 'binary(16) NULL',
        ],
        'categories' => [
            'inputType'  => 'picker',
            'foreignKey' => 'tl_company_category.title',
            'eval'       => ['multiple' => true, 'tl_class' => 'clr'],
            'sql'        => ['type' => 'blob', 'length' => 65535, 'notnull' => false],
            'relation'   => ['type' => 'hasMany', 'load' => 'lazy'],
        ],
        'client' => [
            'inputType'  => 'picker',
            'foreignKey' => 'tl_company_client.name',
            'eval'       => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql'        => "int(10) unsigned NOT NULL default 0",
            'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'project' => [
            'inputType'  => 'picker',
            'foreignKey' => 'tl_company_project.title',
            'eval'       => ['tl_class' => 'w50'],
            'sql'        => "int(10) unsigned NOT NULL default 0",
            'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'date' => [
            'inputType' => 'text',
            'eval' => ['rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'featured' => [
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'published' => [
            'inputType' => 'checkbox',
            'toggle' => true,
            'eval' => ['doNotCopy' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'sorting' => [
            'sql' => "int(10) unsigned NOT NULL default 0",
        ],
    ],
];
