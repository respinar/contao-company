<?php

declare(strict_types=1);

/*
 * This file is part of Contao Testimonials.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

use Contao\Controller;

// Palettes
$GLOBALS['TL_DCA']['tl_content']['palettes']['project_list'] = '{type_legend},type,headline,title;;{project_legend},project_archives;{image_legend},size;{template_legend:hide},customTpl,project_listClass,project_template;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['project_reader'] = '{type_legend},type,headline,title;;{image_legend},size;{template_legend:hide},customTpl,project_template;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';

// Fields
$GLOBALS['TL_DCA']['tl_content']['fields']['project_archives'] =
[
    'inputType' => 'checkboxWizard',
    'foreignKey' => 'tl_company_project_archive.title',
    'eval' => ['multiple' => true, 'mandatory' => true],
    'sql' => ['type' => 'blob', 'length' => 65535, 'notnull' => false],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['project_template'] = [
    'inputType' => 'select',
    'options_callback' => static fn () => Controller::getTemplateGroup('project_'),
    'eval' => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50 clr'],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];
$GLOBALS['TL_DCA']['tl_content']['fields']['project_listClass'] = [
    'inputType' => 'text',
    'eval' => ['maxlength' => 128, 'tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];

/*
 * Content elements
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['testimonial_list'] = '{type_legend},type,headline;{config_legend},testimonial_archives,testimonial_categories,testimonial_featured,numberOfItems,testimonial_order,testimonial_template;{template_legend:hide},customTpl;{protected_legend:hide},protected;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['testimonial_reader'] = '{type_legend},type,headline;{template_legend:hide},customTpl;{protected_legend:hide},protected;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['testimonial_template'] = [
    'inputType' => 'select',
    'options_callback' => static fn (): array => Controller::getTemplateGroup('testimonial_'),
    'eval' => ['tl_class' => 'w50', 'chosen' => true, 'includeBlankOption' => true],
    'sql' => "varchar(64) NOT NULL default ''",
];

/*
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['testimonial_archives'] = [
    'inputType' => 'checkbox',
    'foreignKey' => 'tl_company_testimonial_archive.title',
    'eval' => ['multiple' => true, 'tl_class' => 'clr'],
    'sql' => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['testimonial_categories'] = [
    'inputType' => 'picker',
    'foreignKey' => 'tl_company_category.title',
    'eval' => ['multiple' => true, 'tl_class' => 'clr'],
    'sql' => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['testimonial_featured'] = [
    'inputType' => 'select',
    'options' => ['all', 'featured', 'unfeatured'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['testimonial_featured_options'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(16) NOT NULL default 'all'",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['testimonial_order'] = [
    'inputType' => 'select',
    'options' => ['sorting_asc', 'sorting_desc', 'date_asc', 'date_desc', 'random'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['testimonial_order_options'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(16) NOT NULL default 'sorting_asc'",
];

// Add palette for client_list content element
$GLOBALS['TL_DCA']['tl_content']['palettes']['client_list'] = '{type_legend},type,headline;{client_legend},clientGroup;{template_legend:hide},customTpl,client_listClass,client_template,numberOfItems;{image_legend},size;{protected_legend:hide},protected;{expert_legend:hide},cssID;{invisible_legend:hide},invisible,start,stop';

// Add clientGroup field
$GLOBALS['TL_DCA']['tl_content']['fields']['clientGroup'] = [
    'inputType' => 'select',
    'foreignKey' => 'tl_company_client_group.title',
    'eval' => ['mandatory' => true, 'chosen' => true, 'tl_class' => 'w50'],
    'sql' => 'int(10) unsigned NOT NULL default 0',
    'relation' => ['type' => 'belongsTo', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['client_template'] = [
    'inputType' => 'select',
    'options_callback' => static fn () => Controller::getTemplateGroup('client_'),
    'eval' => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50 clr'],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];
$GLOBALS['TL_DCA']['tl_content']['fields']['client_listClass'] = [
    'inputType' => 'text',
    'eval' => ['maxlength' => 128, 'tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];
