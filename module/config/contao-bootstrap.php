<?php

return array(
    'config' => array(
        'types' => array(
            'modal' => 'Netzmacht\Bootstrap\Modal\ConfigModalType',
        ),
    ),

    'modal'      => array(
        'dismiss'    => '<span aria-hidden="true">&times;</span>',
        'adjustForm' => true,
    ),
    'templates'  => array(
        'parsers' => array(
            'callback_replace-classes' => array(
                'templates' => array('mod_bootstrap_modal*'),
            ),
        ),
        'modifiers' => array(
            'callback_replace-image-classes' => array(
                'templates' => array('mod_bootstrap_modal*'),
            ),
            'callback_replace-table-classes' => array(
                'templates' => array('mod_bootstrap_modal*'),
            ),
        ),
    ),
);
