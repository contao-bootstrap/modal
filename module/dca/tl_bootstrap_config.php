<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

/*
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_bootstrap_config']['metapalettes']['modal extends default'] = array
(
    '+config' => array(
        'modal_dismiss',
        'modal_adjustForm'
    ),
);


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['modal_adjustForm'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['modal_adjustForm'],
    'inputType' => 'checkbox',
    'eval'      => array(
        'tl_class'       => 'w50',
    ),
    'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['modal_dismiss'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['modal_dismiss'],
    'inputType' => 'text',
    'eval'      => array(
        'tl_class'       => 'w50',
        'maxlength'      => 128,
    ),
    'sql'       => "varchar(128) NOT NULL default ''"
);
