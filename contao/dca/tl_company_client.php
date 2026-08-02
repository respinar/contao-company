<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_company_client'] = [
    'config' => [
        'dataContainer'    => DC_Table::class,
        'enableVersioning' => true,
        'ptable'           => 'tl_company_client_group',
        'sql'              => [
            'keys' => [
                'id'        => 'primary',
                'alias'     => 'index',
                'published' => 'index',
            ],
        ],
    ],

    'list' => [
        'sorting' => [
            'mode'         => 4,
            'fields'       => ['name'],
            'flag'         => 1,
            'headerFields' => ['title', 'alias'],
            'panelLayout'  => 'filter;sort,search,limit',
        ],
    ],

    'palettes' => [
        'default' => '{title_legend},name,alias;{logo_legend},logo;{details_legend},website,location,description;{category_legend},categories;{publish_legend},published,start,stop',
    ],

    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ],
        'pid' => [
            'foreignKey' => 'tl_company_client_group.title',
            'sql'        => "int(10) unsigned NOT NULL default 0",
            'relation'   => ['type' => 'belongsTo', 'load' => 'eager'],
        ],
        'name' => [
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'alias' => [
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'alias', 'doNotCopy' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'sql'       => "varchar(128) COLLATE utf8mb4_bin NOT NULL default ''",
        ],
        'logo' => [
            'inputType' => 'fileTree',
            'eval'      => ['filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpeg,png,gif,svg,webp', 'tl_class' => 'clr'],
            'sql'       => 'binary(16) NULL',
        ],
        'website' => [
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'url', 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'description' => [
            'search'    => true,
            'inputType' => 'textarea',
            'eval'      => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql'       => 'mediumtext NULL',
        ],
        'location' => [
            'inputType' => 'picker',
            'foreignKey'=> 'tl_company_location.title',
            'eval'      => ['tl_class' => 'w50'],
            'sql'        => ['type' => 'blob', 'length' => 65535, 'notnull' => false],
            'relation'   => ['type' => 'hasMany', 'load' => 'lazy'],
        ],
        'categories' => [
            'inputType'  => 'picker',
            'foreignKey' => 'tl_company_category.title',
            'eval'       => ['multiple' => true, 'tl_class' => 'clr'],
            'sql'        => ['type' => 'blob', 'length' => 65535, 'notnull' => false],
            'relation'   => ['type' => 'hasMany', 'load' => 'lazy'],
        ],
        'published' => [
            'toggle'    => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'eval'      => ['doNotCopy' => true],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'start' => [
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
        'stop' => [
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''",
        ],
    ],
];
