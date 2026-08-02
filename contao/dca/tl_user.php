<?php

declare(strict_types=1);

/*
 * This file is part of Contao Clients Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;

// Extend the default palettes
PaletteManipulator::create()
	->addLegend('clients_legend', 'amg_legend', PaletteManipulator::POSITION_BEFORE)
	->addField('clients', 'clients_legend', PaletteManipulator::POSITION_APPEND)
	->applyToPalette('extend', 'tl_user')
	->applyToPalette('custom', 'tl_user')
;

// Add fields to tl_user
$GLOBALS['TL_DCA']['tl_user']['fields']['clients'] = array
(
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_company_client_group.title',
	'eval'                    => array('multiple'=>true),
	'sql'                     => array('type'=>'blob', 'length'=>AbstractMySQLPlatform::LENGTH_LIMIT_BLOB, 'notnull'=>false),
	'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
);


// Extend the default palettes
PaletteManipulator::create()
	->addLegend('projects_legend', 'amg_legend', PaletteManipulator::POSITION_BEFORE)
	->addField('projects', 'projects_legend', PaletteManipulator::POSITION_APPEND)
	->applyToPalette('extend', 'tl_user')
	->applyToPalette('custom', 'tl_user')
;

// Add fields to tl_user
$GLOBALS['TL_DCA']['tl_user']['fields']['projects'] = array
(
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_company_project_archive.title',
	'eval'                    => array('multiple'=>true),
	'sql'                     => array('type'=>'blob', 'length'=>AbstractMySQLPlatform::LENGTH_LIMIT_BLOB, 'notnull'=>false),
	'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
);


// Extend the default palettes
PaletteManipulator::create()
	->addLegend('testimonials_legend', 'amg_legend', PaletteManipulator::POSITION_BEFORE)
	->addField('testimonials', 'testimonials_legend', PaletteManipulator::POSITION_APPEND)
	->applyToPalette('extend', 'tl_user')
	->applyToPalette('custom', 'tl_user')
;

// Add fields to tl_user
$GLOBALS['TL_DCA']['tl_user']['fields']['testimonials'] = array
(
	'inputType'               => 'checkbox',
	'foreignKey'              => 'tl_company_testimonials_archive.title',
	'eval'                    => array('multiple'=>true),
	'sql'                     => array('type'=>'blob', 'length'=>AbstractMySQLPlatform::LENGTH_LIMIT_BLOB, 'notnull'=>false),
	'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
);
