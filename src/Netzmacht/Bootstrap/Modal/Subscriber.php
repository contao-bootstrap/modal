<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Modal;

use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Event\InitializeEnvironmentEvent;
use Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagsEvent;
use Netzmacht\Contao\FormHelper\Event\ViewEvent;
use Netzmacht\Html\Element;
use Netzmacht\Html\Element\Standalone;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Subscriber handles events which are related for the modal component.
 *
 * @package Netzmacht\Bootstrap\Components\Modal
 */
class Subscriber implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array (
            InitializeEnvironmentEvent::NAME => 'presetConfig',
            ReplaceInsertTagsEvent::NAME     => array(
                array('replaceModalInsertTag'),
                array('replaceModalLinkInsertTag'),
                array('replaceModalButtonInsertTag')
            ),
            'form-helper.generate-view'      => 'createModalFooter',
        );
    }

    /**
     * Append modals to the html body.
     *
     * @param string $buffer Frontend template output buffer.
     *
     * @return string
     */
    public function appendModals($buffer)
    {
        $modals = implode('', Bootstrap::getConfigVar('runtime.modals', array()));
        $modals = str_replace(array('{{request_token}}', '[{]', '[}]'), array(REQUEST_TOKEN, '{{', '}}'), $modals);

        return str_replace('</body>', $modals . '</body>', $buffer);
    }

    /**
     * Preset modal config.
     *
     * @param InitializeEnvironmentEvent $event Initialize Bootstrap environment event.
     *
     * @return void
     */
    public function presetConfig(InitializeEnvironmentEvent $event)
    {
        $config = $event->getEnvironment()->getConfig();
        $config->set('runtime.modal-footer', false);
    }

    /**
     * Create modal footer.
     *
     * @param ViewEvent $event Form helper view event.
     *
     * @return void
     */
    public function createModalFooter(ViewEvent $event)
    {
        $widget  = $event->getWidget();
        $element = $event->getContainer()->getElement();

        if ($this->isPartOfModalFooter($widget)) {
            $buttons = (array) Bootstrap::getConfigVar('runtime.modal-footer');

            // create copy for footer
            /** @var Element $element */
            $copy = clone $element;
            $copy->setAttribute('onclick', sprintf('$(\'#ctrl_%s\').click();', $widget->id));
            $copy->setId('md_' . $element->getId());
            $copy->addClass('btn');

            $event->getView()->getAttributes()->addClass('sr-only');

            $buttons[] = $copy;
            Bootstrap::setConfigVar('runtime.modal-footer', $buttons);
        }
    }

    /**
     * Replace modal insert tag.
     *
     * This insert tag will replace the modal::* insert tag. It only creates an link.
     *
     * Supported formats are:
     *
     * modal_link::id
     *  - Creates an link to a modal by using modal modul name as label.
     *
     * modal_link::id::Custom title
     *  - creates an link to a modal with a custom title
     *
     * @param ReplaceInsertTagsEvent $event Replace insert tag event.
     *
     * @return void
     */
    public function replaceModalLinkInsertTag(ReplaceInsertTagsEvent $event)
    {
        if ($event->getTag() === 'modal_link') {
            $element = $this->createLink($event->getParam(0), $event->getParam(1));

            if ($element) {
                $event->setHtml($element->generate());
            }
        }
    }

    /**
     * Replace modal insert tag.
     *
     * This insert tag will replace the modal::* insert tag. It only creates an link.
     *
     * Supported formats are:
     *
     * modal_btn::id::default sm
     *  - Creates an link to a modal by using modal modul name as label.
     *  - Transforms 2nd param to btn-* classes
     *
     * modal_btn::id::default sm::Custom title
     *  - creates an link to a modal with a custom title
     *
     * @param ReplaceInsertTagsEvent $event Replace insert tag event.
     *
     * @return void
     */
    public function replaceModalButtonInsertTag(ReplaceInsertTagsEvent $event)
    {
        if ($event->getTag() === 'modal_btn') {
            $element = $this->createLink($event->getParam(0), $event->getParam(2));
            $element
                ->addClass('btn');

            $classes = array_filter(explode(' ', $event->getParam(1)));
            $classes = array_map(
                function ($class) {
                    return 'btn-' . $class;
                },
                $classes
            );

            if ($classes) {
                $element->addClasses($classes);
            } else {
                $element->addClass('btn-default');
            }

            if ($element) {
                $event->setHtml($element->generate());
            }
        }
    }

    /**
     * Replace modal tag.
     *
     * Modal tag supports following formats:
     *
     * modal::id
     *      - SimpleAjax.php?modal=id?page=Pageid
     * modal::link::id
     *      - <a href="#modal-id" data-toggle="modal">Module name</a>
     * modal::url::id
     *      - #modal-id
     * modal::link::id::title
     *      - <a href="#modal-id" data-toggle="modal">Title</a>
     *
     * modal::id::type::typeid
     *      - SimpleAjax.php?modal=id?page=Pageid&dynamic=type&id=typeid
     * modal::link::id::type::typeid
     *      - <a href="SimpleAjax.php?modal=id?page=Pageid&dynamic=type&id=typeid" data-toggle="modal"
     *              data-remote="SimpleAjax.php?modal=id?page=Pageid&dynamic=type&id=typeid">Module name</a>
     *      - The data-target is nessecary because Bootstrap ony checks for data-remote if content is cached
     *      - href attribute is set for accessibility issues
     * modal::link::id::type::typeid::title
     *      - <a href="SimpleAjax.php?modal=id?page=Pageid&dynamic=type&id=typeid" data-toggle="modal"
     *              data-remote="SimpleAjax.php?modal=id?page=Pageid&dynamic=type&id=typeid">Title</a>
     *
     * modal::url::
     *
     * @param ReplaceInsertTagsEvent $event Replace insert tag event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function replaceModalInsertTag(ReplaceInsertTagsEvent $event)
    {
        if ($event->getTag() != 'modal') {
            return;
        }

        $params = $this->prepareParams($event);
        $this->loadModal($params[1]);

        $count = count($params);

        if ($count == 2 || $count == 3) {
            switch ($params[0]) {
                case 'remote':
                    $buffer = $this->generateRemoteInsertTag($params);
                    break;

                case 'url':
                case 'link':
                    $buffer = $this->generateLinkInsertTag($params);
                    break;

                default:
                    return;
            }

            $event->setHtml($buffer);
        }

        $this->replaceDeprecatedInsertTag($event, $params, $count);
    }

    /**
     * Consider if form widget should be moved to the modal footer.
     *
     * @param \Widget $widget Form widget.
     *
     * @return bool
     */
    protected function isPartOfModalFooter(\Widget $widget)
    {
        $isModal =
            Bootstrap::getConfigVar('runtime.modal-footer') !== false &&
            Bootstrap::getConfigVar('modal.adjustForm') &&
            Bootstrap::getConfigVar(sprintf('form.widgets.%s.modal-footer', $widget->type));

        return $isModal;
    }

    /**
     * Prepare insert tag params.
     *
     * @param ReplaceInsertTagsEvent $event Replace insert tag event.
     *
     * @return array
     */
    private function prepareParams(ReplaceInsertTagsEvent $event)
    {
        $params = $event->getParams();

        if (is_numeric($params[0])) {
            array_insert($params, 0, array('remote'));

            return $params;
        }

        return $params;
    }

    /**
     * Load modal.
     *
     * @param int $modalId Modal model id.
     *
     * @return void
     */
    private function loadModal($modalId)
    {
        if (!Bootstrap::getConfigVar('runtime.modals.' . $modalId)) {
            \Controller::getFrontendModule($modalId);
        }
    }

    /**
     * Generate remote insert tag.
     *
     * @param array $params Insert tag params.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function generateRemoteInsertTag($params)
    {
        if (TL_MODE === 'FE') {
            $buffer = \Controller::generateFrontendUrl($GLOBALS['objPage']->row()) . '?bootstrap_modal=' . $params[1];

            return $buffer;
        } else {
            $buffer = '{{modal::' . $params[1] . '}}';

            return $buffer;
        }
    }

    /**
     * Generate link insert tag.
     *
     * @param array $params Insert tag params.
     *
     * @return string
     */
    private function generateLinkInsertTag($params)
    {
        $model = \ModuleModel::findByPk($params[1]);
        $count = count($params);

        if ($model === null || $model->type != 'bootstrap_modal') {
            return '';
        }

        $cssId  = deserialize($model->cssID, true);
        $buffer = '#' . ($cssId[0] != '' ? $cssId[0] : 'modal-' . $model->id);

        if ($params[0] === 'link') {
            $params[2] = ($count == 3) ? $params[2] : $model->name;
            $buffer    = sprintf('<a href="%s" data-toggle="modal">%s</a>', $buffer, $params[2]);
        }

        return $buffer;
    }

    /**
     * Replace deprecated insert tag.
     *
     * @param ReplaceInsertTagsEvent $event  Replace insert tag event.
     * @param array                  $params Insert tag params.
     * @param int                    $count  Number of params.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function replaceDeprecatedInsertTag(ReplaceInsertTagsEvent $event, $params, $count)
    {
        if (($count == 4 || $count == 5) && in_array($params[0], array('url', 'link', 'remote'))) {
            $params[0] = $GLOBALS['objPage']->id;
            $buffer    = vsprintf(Bootstrap::getConfigVar('modal.remoteDynamicUrl'), $params);

            if ($params[0] === 'link' && $count === 4) {
                $model = \ModuleModel::findByPk($params[1]);

                if ($model === null || $model->type != 'bootstrap_modal') {
                    return;
                }

                $params[6] = $model->name;

                $cssId  = deserialize($model->cssID, true);
                $cssId  = '#' . ($cssId[0] != '' ? $cssId[0] : 'modal-' . $model->id);
                $buffer = sprintf(
                    '<a href="%s" data-toggle="modal" data-target="%s">%s</a>',
                    $buffer,
                    $cssId,
                    $params[6]
                );
            }

            $event->setHtml($buffer);
        }
    }

    /**
     * Create link from insert tag.
     *
     * @param int    $modelId Modal model id.
     * @param string $label   Optional custom label.
     *
     * @return Standalone|null
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function createLink($modelId, $label = null)
    {
        $model = \ModuleModel::findByPk($modelId);
        if (!$model) {
            return null;
        }

        $cssId    = deserialize($model->cssID, true);
        $selector = '#' . ($cssId[0] ?: 'modal-' . $model->id);

        $this->loadModal($modelId);

        $element = Element::create('a');

        if ($model->bootstrap_modalAjax && TL_MODE === 'FE') {
            $page = $GLOBALS['objPage']->row();
            $link = \Controller::generateFrontendUrl($page) . '?bootstrap_modal=' . $model->id;

            $element
                ->setAttribute('href', $link)
                ->setAttribute('data-target', $selector);
        } else {
            $element->setAttribute('href', $selector);
        }

        $element->setAttribute('data-toggle', 'modal');

        if (!$label) {
            $element->addChild($model->name);
        } else {
            $element->addChild($label);
        }

        return $element;
    }
}
