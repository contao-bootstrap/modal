<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_module']['metapalettes']['bootstrap_modal'] = array
(
    'title'     => array('name', 'headline', 'type', 'bootstrap_modalSize'),
    'body'      => array('bootstrap_modalAjax', 'bootstrap_modalContentType'),
    'footer'    => array('bootstrap_addModalFooter'),
    'protected' => array(':hide', 'protected'),
    'expert'    => array(':hide', 'guests', 'cssID', 'space'),
);

/**
 * subpalettes
 */
$GLOBALS['TL_DCA']['tl_module']['metasubselectpalettes']['bootstrap_modalContentType'] = array
(
    'article'  => array('bootstrap_article'),
    'text'     => array('bootstrap_text'),
    'html'     => array('html'),
    'module'   => array('bootstrap_module'),
    'form'     => array('form'),
    'url'      => array('bootstrap_remoteUrl'),
    'template' => array('bootstrap_modalTemplate'),
);

$GLOBALS['TL_DCA']['tl_module']['metasubpalettes']['bootstrap_addModalFooter'] = array
(
    'bootstrap_addCloseButton',
    'bootstrap_buttons',
);

$GLOBALS['TL_DCA']['tl_module']['fields']['html']['eval']['tl_class'] = 'clr long';

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_addModalFooter'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_addModalFooter'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'default'   => true,
    'eval'      => array('submitOnChange' => true, 'tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_addModalButton'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_addModalButton'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'default'   => true,
    'eval'      => array('tl_class' => 'w50'),
    'sql'       => "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_modalContentType'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_modalContentType'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('article', 'text', 'html', 'module', 'form', 'template'),
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_modalContentType_types'],
    'eval'      => array('submitOnChange' => true, 'helpwizard' => true, 'tl_class' => 'w50'),
    'sql'       => "varchar(10) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_modalSize'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_modalSize'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('modal-lg', 'modal-sm'),
    'eval'      => array('includeBlankOption' => true, 'tl_class' => 'w50 clr'),
    'sql'       => "varchar(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_module'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_module'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => array('Netzmacht\Bootstrap\Core\Contao\DataContainer\Module', 'getAllModules'),
    'eval'             => array('chosen' => true, 'tl_class' => 'w50'),
    'sql'              => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_article'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_article'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => array('Netzmacht\Bootstrap\Core\Contao\DataContainer\Module', 'getAllArticles'),
    'eval'             => array('chosen' => true, 'tl_class' => 'w50'),
    'sql'              => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_text'] = array
(
    'label'       => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_text'],
    'exclude'     => true,
    'search'      => true,
    'inputType'   => 'textarea',
    'eval'        => array('mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true, 'tl_class' => 'clr long'),
    'explanation' => 'insertTags',
    'sql'         => "mediumtext NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_modalTemplate'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_modalTemplate'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => array('Netzmacht\Bootstrap\Core\Contao\DataContainer\Module', 'getTemplates'),
    'reference'        => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'             => array('templateThemeId' => 'pid', 'chosen' => true, 'tl_class' => 'w50'),
    'sql'              => "varchar(64) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_modalAjax'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['bootstrap_modalAjax'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'default'   => true,
    'eval'      => array('tl_class' => 'w50 m12'),
    'sql'       => "char(1) NOT NULL default ''",
);
