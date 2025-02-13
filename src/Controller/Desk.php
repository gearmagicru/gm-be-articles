<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Controller;

use Gm;
use Gm\Panel\Http\Response;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Widget\TabWidget;
use Gm\Panel\Controller\BaseController;

/**
 * Контроллер элементов панели справочников.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Controller
 * @since 1.0
 */
class Desk extends BaseController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Articles\Module
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    protected string $defaultAction = 'view';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabWidget
    {
        /** @var TabWidget $tab */
        $tab = new TabWidget();

        // панель вкладки компонента (Ext.tab.Panel SenchaJS)
        $tab->id = $this->module->viewId('tab'); 
        $tab->title = '#{name}';
        $tab->tooltip = [
            'icon'  => $tab->imageSrc('/icon.svg'),
            'title' => '#{name}',
            'text'  => '#{description}'
        ];
        $tab->icon = $tab->imageSrc('/icon_small.svg');
        $tab->dockedItems = [
            'dock'       => 'right',
            'xtype'      => 'toolbar',
            'cls'        => 'g-toolbar-flat',
            'style'      => 'background-color:#e7e7e7',
            'controller' => 'shortcuts',
            'items'      => [
                [
                    'xtype'   => 'button',
                    'cls'     => 'g-button-tool',
                    'tooltip' => '#Refresh',
                    'iconCls' => 'g-icon-tool g-icon-tool_default x-tool-refresh',
                    'handler' => 'refreshShortcuts'
                ],
                [
                    'xtype'   => 'button',
                    'cls'     => 'g-button-tool',
                    'tooltip' => '#Help',
                    'iconCls' => 'g-icon-tool g-icon-tool_default x-tool-help',
                    'handlerArgs' => ['route' => Gm::alias('@backend', '/guide/modal/view?component=' . $this->module->getId(true) . '&subject=desk')],
                    'handler'     => 'loadWidget'
                ]
            ]
        ];
        
        // элементы рабочего стола (Gm.view.shortcuts.Shortcuts Gm JS)
        $tab->items = [
            'id'     => $this->module->viewId('shortcuts'),
            'xtype'  => 'g-shortcuts',
            'router' => [
                'route' => \Gm::alias('@route', '/desk'),
                'rules' => [
                    'data' => '{route}/data'
                ]
            ]
        ];

        $tab->addRequire('Gm.view.shortcuts.Shortcuts');
        return $tab;
    }

    /**
     * Действие "view" возвращает интерфейса панели.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var TabWidget $widget */
        $widget = $this->getWidget();
        // если была ошибка при формировании виджета
        if ($widget === false) {
            return $response;
        }

        $response
            ->setContent($widget->run())
            ->meta
                ->addWidget($widget);
        return $response;
    }

    /**
     * Действие "data" выполняет вывод элементов панели.
     * 
     * @return Response
     */
    public function dataAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var ShortcutsModel $shortcuts */
        $shortcuts = $this->getModel('Desk');
        return $response->setContent($shortcuts->getItems());
    }
}
