<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Modal\Config;

use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Config\Type;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;

/**
 * ModalType handles modal configuration.
 *
 * @package Netzmacht\Bootstrap\Modal\Config
 */
class ModalType implements Type
{
    /**
     * {@inheritdoc}
     */
    public function buildConfig(Config $config, BootstrapConfigModel $model)
    {
        $config
            ->set('modal.dismiss', (bool) $model->modal_dismiss)
            ->set('modal.adjustForm', (bool) $model->modal_adjustForm);
    }

    /**
     * {@inheritdoc}
     */
    public function extractConfig($key, Config $config, BootstrapConfigModel $model)
    {
        $model->modal_dismiss    = $config->get('modal.dismiss');
        $model->modal_adjustForm = $config->get('modal.adjustForm');
    }

    /**
     * {@inheritdoc}
     */
    public function hasGlobalScope()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isMultiple()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isNameEditable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return 'modal';
    }
}
