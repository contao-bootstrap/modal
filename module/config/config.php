<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

// Frontend modules
$GLOBALS['FE_MOD']['miscellaneous']['bootstrap_modal'] = 'Netzmacht\Bootstrap\Modal\ModalModule';

// Hooks
$GLOBALS['TL_HOOKS']['modifyFrontendPage'][] = array('Netzmacht\Bootstrap\Modal\Subscriber', 'appendModals');

// Event Subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Modal\Subscriber';
