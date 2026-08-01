<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_company_location'] = [
    'config' => [
        'dataContainer'    => DC_Table::class,
        'enableVersioning' => true,
        'markAsCopy'       => 'title',
        'sql'              => [
            'keys' => [
                'id'  => 'primary',
                'pid' => 'index',
            ],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode'                    => DataContainer::MODE_TREE,
            'rootPaste'               => true,
            'panelLayout'             => 'filter;search',
        ],
        'label' => [
            'fields'                  => ['title'],
            'format'                  => '%s',
        ],
    ],

    'palettes' => [
        'default'                     => '{title_legend},title,alias;{desc_legend},description',
    ],

    'fields' => [
        'id' => [
            'sql'                     => "int(10) unsigned NOT NULL auto_increment",
        ],
        'pid' => [
            'sql'                     => "int(10) unsigned NOT NULL default 0",
        ],
        'sorting' => [
            'sql'                     => "int(10) unsigned NOT NULL default 0",
        ],
        'tstamp' => [
            'sql'                     => "int(10) unsigned NOT NULL default 0",
        ],
        'title' => [
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''",
        ],
        'alias' => [
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => ['rgxp' => 'alias', 'doNotCopy' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'sql'                     => "varchar(128) BINARY NOT NULL default ''",
        ],
        'description' => [
            'inputType'               => 'textarea',
            'search'                  => true,
            'eval'                    => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql'                     => "text NULL",
        ],
    ],
];
