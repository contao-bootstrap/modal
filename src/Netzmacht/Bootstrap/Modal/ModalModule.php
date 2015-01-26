<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */


namespace Netzmacht\Bootstrap\Modal;

use Module;
use Netzmacht\Bootstrap\Buttons\Factory;
use Netzmacht\Bootstrap\Buttons\Group;
use Netzmacht\Bootstrap\Buttons\Toolbar;
use Netzmacht\Bootstrap\Modal\Modal as Component;
use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Html\Attributes;
use Netzmacht\Html\Element;
use Netzmacht\Html\Element\StaticHtml;

/**
 * Modal frontend module.
 *
 * @package Netzmacht\Bootstrap\Components\Contao\Module
 */
class ModalModule extends \Module
{
    /**
     * Tempalte name.
     *
     * @var string
     */
    protected $strTemplate = 'mod_bootstrap_modal';

    /**
     * Form buttons.
     *
     * @var array
     */
    private $formButtons;

    /**
     * Compile the module.
     *
     * @return void
     */
    protected function compile()
    {
        if ($this->cssID[0] == '') {
            $cssID       = $this->cssID;
            $cssID[0]    = 'modal-' . $this->id;
            $this->cssID = $cssID;
        }

        $modal = new Component();
        $modal
            ->setId($this->cssID[0])
            ->setSize($this->bootstrap_modalSize)
            ->setAttribute('role', 'dialog')
            ->setAttribute('aria-hidden', 'true')
            ->setAttribute('taxindex', '-1');

        if ($this->cssID[1]) {
            $modal->addClass($this->cssID[1]);
        }

        // check if ajax is used
        if ($this->bootstrap_modalAjax) {
            $this->Template->hideFrame   = $this->isAjax;
            $this->Template->hideContent = !$this->Template->hideFrame;
        }

        if ($this->Template->hideContent) {
            $modal->render($this->Template);

            return;
        }

        if ($this->headline) {
            $headline = Element::create($this->hl)
                ->addClass('modal-title')
                ->addChild($this->headline);

            $modal->setTitle($headline);
        }

        $modal
            ->setContent($this->getContent())
            ->setFooter($this->getButtons())
            ->setCloseButton(Bootstrap::getConfigVar('modal.dismiss'), true)
            ->render($this->Template);
    }

    /**
     * Generate the modal.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template           = new \FrontendTemplate('be_wildcard');
            $template->wildcard = '### modal window ###';

            return $template->parse();
        }

        if (\Input::get('bootstrap_modal') == $this->id) {
            if ($this->bootstrap_modalAjax) {
                $this->isAjax = true;
            }
        }

        $content = parent::generate();
        $content = $this->replaceInsertTags($content);

        // add content to TL_BODY
        if ($this->isAjax) {
            echo $content;
            exit;
        }

        Bootstrap::setConfigVar('runtime.modals.' . $this->id, $content);

        return '';
    }

    /**
     * Get modal content.
     *
     * @return string
     */
    private function getContent()
    {
        $config = Bootstrap::getConfig();

        switch ($this->bootstrap_modalContentType) {
            case 'article':
                return $this->getArticle($this->bootstrap_article, false, true);

            case 'form':
                return $this->generateForm($config);

            case 'module':
                return $this->getFrontendModule($this->bootstrap_module);

            case 'html':
                return (TL_MODE == 'FE') ? $this->html : htmlspecialchars($this->bootstrap_html);

            case 'template':
                ob_start();
                include $this->getTemplate($this->bootstrap_modalTemplate);
                $buffer = ob_get_contents();
                ob_end_clean();

                return $buffer;

            case 'text':
                return \String::toHtml5($this->bootstrap_text);

            default:
                return '';
        }
    }

    /**
     * Get the buttons toolbar/group.
     *
     * @return Group|Toolbar|string
     */
    public function getButtons()
    {
        if ($this->bootstrap_addModalFooter) {
            $style   = $this->bootstrap_buttonStyle ?: 'btn-default';
            $buttons = Factory::createFromFieldset($this->bootstrap_buttons);

            if ($this->formButtons) {
                $old     = $buttons;
                $buttons = Factory::createGroup();

                foreach ($this->formButtons as $button) {
                    if (is_string($button)) {
                        $button = new StaticHtml($button);
                    }

                    $buttons->addChild($button);
                }

                foreach ($old->getChildren() as $button) {
                    $buttons->addChild($button);
                }
            }

            $buttons->eachChild(
                function ($item) use ($style) {
                    if ($item instanceof Attributes) {
                        $classes = $item->getAttribute('class');
                        $classes = array_filter(
                            $classes,
                            function ($class) {
                                return strpos($class, 'btn-') !== false;
                            }
                        );

                        if (empty($classes)) {
                            $item->addClass($style);
                        }
                    }
                }
            );

            $buttons->removeClass('btn-group');
        } else {
            $buttons = implode('', (array) $this->formButtons);
        }

        return $buttons;
    }

    /**
     * Generate the form.
     *
     * @param Config $config Bootstrap config.
     *
     * @return string
     */
    private function generateForm($config)
    {
        $config->set('runtime.modal-footer', '');
        $content           = $this->getForm($this->form);
        $this->formButtons = $config->get('runtime.modal-footer');
        $config->set('runtime.modal-footer', false);

        // render style select if it is used
        // @codingStandardsIgnoreStart
        // TODO move this to an event or hook
        // @codingStandardsIgnoreEnd
        if ($this->isAjax && $config->get('form.styleSelect.enabled')) {
            $content .= sprintf(
                '<script>jQuery(\'.%s\').selectpicker(\'render\');</script>',
                $config->get('form.styleSelect.class')
            );
        }

        return $content;
    }
}
