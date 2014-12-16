<?php

// Frontend modules
$GLOBALS['FE_MOD']['miscellaneous']['bootstrap_modal'] = 'Netzmacht\Bootstrap\Modal\ModalModule';

// Hooks
$GLOBALS['TL_HOOKS']['modifyFrontendPage'][] = array('Netzmacht\Bootstrap\Modal\Subscriber', 'appendModals');

// Event Subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Components\Modal\Subscriber';
