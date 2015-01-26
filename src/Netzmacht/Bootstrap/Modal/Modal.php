<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Modal;

use Netzmacht\Html\Attributes;
use Netzmacht\Html\CastsToString;
use Netzmacht\Html\Element\StaticHtml;
use Netzmacht\Html\Element;

/**
 * Class Modal is used to render modal elements.
 *
 * @package Netzmacht\Bootstrap\Components\Modal
 */
class Modal extends Attributes
{
    /**
     * Close button.
     *
     * @var CastsToString
     */
    private $closeButton;

    /**
     * Title.
     *
     * @var CastsToString
     */
    private $title;

    /**
     * Modal size.
     *
     * @var string
     */
    private $size;

    /**
     * Modal content.
     *
     * @var CastsToString
     */
    private $content;

    /**
     * Modal footer.
     *
     * @var CastsToString
     */
    private $footer;

    /**
     * Template name.
     *
     * @var string
     */
    private $template = 'bootstrap_modal';

    /**
     * Construct.
     *
     * @param array       $attributes Optional attributes.
     * @param string|null $template   Optional template.
     */
    public function __construct(array $attributes = array(), $template = null)
    {
        $attributes = array_merge(
            array(
                'class' => array('modal', 'fade')
            ),
            $attributes
        );

        if ($template) {
            $this->template = $template;
        }

        parent::__construct($attributes);
    }

    /**
     * Generate the modal by using the template.
     *
     * @return string
     */
    public function generate()
    {
        $template = new \FrontendTemplate($this->template);
        $this->render($template);

        return $template->parse();
    }

    /**
     * Render the modal to a specific template.
     *
     * @param \Template $template Template name.
     *
     * @return $this
     */
    public function render(\Template $template)
    {
        $template->attributes  = parent::generate();
        $template->closeButton = $this->closeButton;
        $template->title       = $this->title;
        $template->content     = $this->content;
        $template->footer      = $this->footer;
        $template->size        = $this->size ? (' ' . $this->size) : '';

        return $this;
    }

    /**
     * Set close button.
     *
     * @param CastsToString|string $closeButton Close button as string or CastsToString element.
     * @param bool                 $create      Set true if just a string is given and the button shall be created.
     *
     * @return $this
     */
    public function setCloseButton($closeButton, $create = false)
    {
        if ($create) {
            $closeButton = Element::create('button')
                ->addClass('close')
                ->setAttribute('data-dismiss', 'modal')
                ->setAttribute('aria-hidden', 'true')
                ->addChild($closeButton);
        }

        $this->closeButton = $closeButton;

        return $this;
    }

    /**
     * Get the close button.
     *
     * @return CastsToString
     */
    public function getCloseButton()
    {
        return $this->closeButton;
    }

    /**
     * Set the content.
     *
     * @param CastsToString|string $content Modal content.
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $this->convertToCastsToString($content);

        return $this;
    }

    /**
     * Get the content.
     *
     * @return CastsToString
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the footer.
     *
     * @param CastsToString|string $footer Modal footer content.
     *
     * @return $this
     */
    public function setFooter($footer)
    {
        $this->footer = $this->convertToCastsToString($footer);

        return $this;
    }

    /**
     * Get the footer.
     *
     * @return CastsToString|string
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * Set the title.
     *
     * @param CastsToString|string $title Modal title.
     *
     * @return $this
     */
    public function setTitle($title)
    {
        if ($title instanceof Attributes) {
            $title->addClass('modal-title');
        } else {
            $title = $this->convertToCastsToString($title);
        }

        $this->title = $title;

        return $this;
    }

    /**
     * Set the size.
     *
     * @param string $size Bootstrap modal size class.
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the size.
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get the title.
     *
     * @return CastsToString
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the template.
     *
     * @param string $template Tempalte name.
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the template name.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Convert string to CastsToString.
     *
     * @param mixed $content Given content.
     *
     * @return CastsToString
     */
    private function convertToCastsToString($content)
    {
        if (!$content instanceof CastsToString) {
            $content = new StaticHtml((string) $content);
        }

        return $content;
    }
}
