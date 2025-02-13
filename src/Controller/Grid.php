<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Controller;

use Gm;
use Gm\Helper\Str;
use Gm\Stdlib\BaseObject;
use Gm\Panel\Widget\TabGrid;
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;
use Gm\Panel\Data\Model\FormModel;
use Gm\Panel\Controller\GridController;

/**
 * Контроллер списка статей сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Controller
 * @since 1.0
 */
class Grid extends GridController
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
    public bool $useAppEvents = true;

    /**
     * Тип материала.
     * 
     * @var array|null
     */
    protected ?array $articleType = null;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action, &$result) {
                switch ($action) {
                    case 'view':
                    case 'delete':
                    case 'clear':
                        $typeId = Gm::$app->request->getQuery('type', 0, 'int');
                        break;
    
                    case 'data':
                        $typeId = Gm::$app->request->getPost('type', 0, 'int');
                        break;
                }

                if (isset($typeId)) {
                    if (empty($typeId)) {
                        $this->getResponse()
                            ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['type']));
                        $result = false;
                        return;
                    }

                    // типа материала
                    $type = $this->module->getArticleType($typeId, $action === 'view');
                    if (is_string($type) || $type === null) {
                        $this->getResponse()
                            ->meta->error(
                                is_string($type) ? $type : Gm::t('app', 'Parameter passed incorrectly "{0}"', ['type'])
                            );
                        $result = false;
                        return;
                    }
                    $this->articleType = $type;
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(string $name = null, array $config = []): ?BaseObject
    {
        $config['articleType'] = $this->articleType;

        return parent::getModel($name, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function translateAction(mixed $params, string $default = null): ?string
    {
        switch ($this->actionName) {
            // изменение записи по указанному идентификатору
            case 'update':
                /** @var FormModel $model */
                $model = $this->lastDataModel;
                if ($model instanceof FormModel) {
                    if ($model->publish !== null) {
                        // если статья опубликована
                        $publish = (int) $model->publish;
                        $header  = $model->getUnsafeAttribute('header');
                        if ($header === null) {
                            $header = $model->getIdentifier();
                        }
                        return $this->module->t('article ' . ($publish > 0 ? '' : 'not ') . 'published with header {0}', [$header]);
                    }
                }

            default:
                return parent::translateAction($params, $default);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        /** @var TabGrid $tab Сетка данных (Gm.view.grid.Grid GmJS) */
        $tab = parent::createWidget();

        $tab->id = 'tab-' . $this->articleType['id'];
        $tab->title = $this->module->t($this->articleType['name']);
        if ($icon = $this->articleType['icon']) {
            $tab->setTooltip(['icon' => $icon]);
            $tab->icon = Str::addPefixToFilename($icon, '_small', 'right');
        }
        $tab->setTooltip([
            'title' => $this->module->t($this->articleType['name']),
            'text'  => $this->module->t($this->articleType['description'])
        ]);

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $formats = Gm::$app->formatter->formatsWithoutPrefix();
        $columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'xtype'     => 'templatecolumn',
                'text'      => '#Date of publication',
                'align'     => 'center',
                'tooltip'   => '#Date of publication of the article',
                'dataIndex' => 'publishDate',
                'tpl'       => '{publishDate:date("' . $formats['dateTimeFormat'] . '")}',
                'filter'    => ['type' => 'date', 'dateFormat' => 'Y-m-d'],
                'width'     => 140
            ],
            [
                'text'      => '№',
                'tooltip'   => '#The ordinal number of the article is used to sort the articles in the list',
                'dataIndex' => 'index',
                'filter'    => ['type' => 'number'],
                'width'     => 70
            ],
            [
                'text'    => ExtGrid::columnInfoIcon($this->t('Header')),
                'cellTip' => HtmlGrid::tags([
                    HtmlGrid::header('{header:ellipsis(50)}'),
                    HtmlGrid::fieldLabel(
                        $this->t('Caching'),
                        HtmlGrid::tplChecked('caching==1')
                    ),
                    HtmlGrid::fieldLabel($this->t('Number of hits'), '{hits}'),
                    HtmlGrid::fieldLabel($this->t('Index number'), '{index}'),
                    HtmlGrid::fieldLabel($this->t('Slug type'), '{slugType}'),
                    HtmlGrid::tplIf(
                        'template', HtmlGrid::fieldLabel($this->t('Template'), '{template}'), ''
                    ),
                    HtmlGrid::tplIf(
                        'pageTemplate', HtmlGrid::fieldLabel($this->t('Page template'), '{pageTemplate}'), ''
                    ),
                    HtmlGrid::tplIf(
                        'publishDate', HtmlGrid::fieldLabel($this->t('Language'), '{languageName}'), ''
                    ),
                    HtmlGrid::tplIf(
                        'publishDate',
                        HtmlGrid::fieldLabel($this->t('Date of publication of the article'), '{publishDate:date("' . $formats['dateTimeFormat'] . '")}'),
                        ''
                    ),
                    ['fieldset',
                        [
                            HtmlGrid::legend($this->t('Indexing')),
                            HtmlGrid::tplIf(
                                'metaRobots', 
                                HtmlGrid::fieldLabel($this->t('for all robots'), '{metaRobots}'), 
                                ''
                            ),
                            HtmlGrid::tplIf(
                                'metaYandex', 
                                HtmlGrid::fieldLabel($this->t('robot only "Yandex"'), '{metaYandex}'),
                                ''
                            ),
                            HtmlGrid::tplIf(
                                'metaGoogle',
                                HtmlGrid::fieldLabel($this->t('robot only "Google"'), '{metaGoogle}'), 
                                ''
                            ),
                            HtmlGrid::tplIf('indexing', '', $this->t('the article is not indexed by robots'))
                        ]
                    ],
                    HtmlGrid::tplIf(
                        'categorySlugPath',
                        HtmlGrid::tag('fieldset',[
                            HtmlGrid::legend($this->t('Category')),
                            HtmlGrid::fieldLabel($this->t('Name'), '{categoryName:ellipsis(50)}'),
                            HtmlGrid::fieldLabel($this->t('URL path'), '{categorySlugPath:ellipsis(50)}')
                        ]),
                        ''
                    ),
                    ['fieldset',
                        [
                            HtmlGrid::legend($this->t('Publishing an article')),
                            HtmlGrid::fieldLabel(
                                $this->t('on the main page of the site'),
                                HtmlGrid::tplChecked('publishOnMain==1')
                            ),
                            HtmlGrid::fieldLabel(
                                $this->t('in site sections'),
                                HtmlGrid::tplChecked('publishInCategories==1')
                            ),
                            HtmlGrid::fieldLabel(
                                $this->t('on the site'),
                                HtmlGrid::tplChecked('publish==1')
                            )
                        ]
                    ]
                ]),
                'dataIndex' => 'header',
                'filter'    => ['type' => 'string'],
                'width'     => 220
            ],
            [
                'text'      => '#Category',
                'dataIndex' => 'categoryName',
                'filter'    => ['type' => 'string'],
                'width'     => 180
            ],
            [
                'text'      => '#Slug',
                'tooltip'   => '#The Slug is a version of a name, a unique part of a URL. These are all lowercase letters and only Latin letters, numbers and hyphens.',
                'dataIndex' => 'slug',
                'filter'    => ['type' => 'string'],
                'width'     => 180
            ],
            [
                'text'      => '#Slug type',
                'tooltip'   => '#The type of the slug determines the rules for the formation of the URL of the article',
                'dataIndex' => 'slugType',
                'width'     => 130
            ],
            [
                'text'      => '#Language',
                'dataIndex' => 'languageName',
                'width'     => 120
            ],
            [
                'text'      => '#Template',
                'dataIndex' => 'template',
                'cellTip'   => '{template}',
                'filter'    => ['type' => 'string'],
                'width'     => 150
            ],
            [
                'text'      => '#Page template',
                'dataIndex' => 'pageTemplate',
                'cellTip'   => '{pageTemplate}',
                'filter'    => ['type' => 'string'],
                'width'     => 150
            ],
            [
                'xtype'    => 'templatecolumn',
                'dataIndex' => 'url', // не используется, но необходим для проверки
                'sortable' => false,
                'width'    => 45,
                'align'    => 'center',
                'tpl'      => HtmlGrid::a(
                    '', 
                    '{url}',
                    [
                        'title' => $this->t('View article'),
                        'class' => 'g-icon g-icon-svg g-icon_size_14 g-icon-m_link g-icon-m_color_default g-icon-m_is-hover',
                        'target' => '_blank'
                    ]
                )
            ],
            [
                'text'      => ExtGrid::columnIcon('gm-articles__icon-hits', 'svg'),
                'dataIndex' => 'hits',
                'filter'    => ['type' => 'number'],
                'tooltip'   => '#Number of hits',
                'align'     => 'center',
                'width'     => 50
            ],
            [
                'text'      => ExtGrid::columnIcon('g-icon-m_visible', 'svg'),
                'xtype'     => 'g-gridcolumn-switch',
                'selector'  => 'grid',
                'collectData' => ['header'],
                'dataIndex' => 'publish',
                'filter'    => ['type' => 'boolean']
            ],
            [
                'xtype'     => 'g-gridcolumn-checker',
                'text'      => ExtGrid::columnIcon('gm-articles__icon-caching', 'svg'),
                'tooltip'   => '#Caching',
                'dataIndex' => 'caching',
                'filter'    => ['type' => 'boolean']
            ],
            [
                'text'      => '#Indexing',
                'dataIndex' => 'indexing',
                'filter'    => ['type' => 'string'],
                'width'     => 140
            ],
        ];

        // столбцы сетки
        $gridColumns = [];
        // шаблоны столбцов материала
        $articleColumns = $this->articleType['columns'];
        foreach ($columns as $column) {
            $dataIndex = $column['dataIndex'] ?? '';
            // если есть шаблон столбца
            if (isset($articleColumns[$dataIndex])) {

                $articleColumn = $articleColumns[$dataIndex];
                // если столбец доступен
                if ($articleColumn['enabled']) {
                    // если указан заголовок
                    if (!empty($articleColumn['text'])) {
                        $column['text'] = $articleColumn['text'];
                    }
                    // если указана подсказка
                    if (!empty($articleColumn['tooltip'])) {
                        $column['tooltip'] = $articleColumn['tooltip'];
                    }
                    // если столбец скрыт
                    $column['hidden'] = empty($articleColumn['visible']);
                    // если нет сортировки
                    if (empty($articleColumn['sortable'])) {
                        $column['sortable'] = false;
                    }
                    // если нет фильтра
                    if (empty($articleColumn['filterable'])) {
                        unset($column['filter']);
                    }
                    $gridColumns[] = $column;
                }
            } else
                $gridColumns[] = $column;
        }
        $tab->grid->columns = $gridColumns;

        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $tab->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        // инструмент "Добавить"
                        'add' => [
                            'iconCls'     => 'g-icon-svg gm-articles__icon-add',
                            'handlerArgs' => ['route' => Gm::alias('@match', '/form?type=' . $this->articleType['id'])],
                            'tooltip'     => $this->t('Adding a new record "{0}"', [$this->articleType['name']])
                        ],
                        // инструмент "Удалить"
                        'delete' => [
                            'iconCls' => 'g-icon-svg gm-articles__icon-delete',
                            'tooltip' => $this->t('Deleting selected records "{0}"', [$this->articleType['name']])
                        ],
                        'cleanup' => [
                            'tooltip'    => $this->t('Deleting all records "{0}"', [$this->articleType['name']]),
                            'msgConfirm' => $this->t('Do you really want to delete all records "{0}"?', [$this->articleType['name']])
                        ],
                        '-',
                        'edit',
                        'select',
                        '-',
                        'refresh'
                    ]
                ],
                'columns',
                'search'
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Grid.popupMenu GmJS)
        $tab->grid->popupMenu = [
            'cls'        => 'g-gridcolumn-popupmenu',
            'titleAlign' => 'center',
            'width'      => 150,
            'items'      => [
                [
                    'text'        => '#Edit article',
                    'iconCls'     => 'g-icon-svg g-icon-m_edit g-icon-m_color_default',
                    'handler'     => 'loadWidget',
                    'handlerArgs' => [
                          'route'   => Gm::alias('@match', '/form/view/{id}?type={type}'),
                          'pattern' => 'grid.popupMenu.activeRecord'
                      ]
                ]
            ]
        ];

        // идентификатор
        $tab->grid->id = 'grid-' . $this->articleType['id'];
        // 2-й клик на строке сетки
        $tab->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => Gm::alias('@match', '/form/view/{id}?type={type}')
        ];
        // сортировка сетки по умолчанию
        $tab->grid->sorters = [
           ['property' => 'name', 'direction' => 'ASC']
        ];
        // количество строк в сетке
        $tab->grid->store->pageSize = 50;
        $tab->grid->store->proxy['extraParams'] = ['type' => $this->articleType['id']];
        $tab->grid->router->rules['delete'] = '{route}/delete?type=' . $this->articleType['id'];
        $tab->grid->router->rules['clear'] = '{route}/clear?type=' . $this->articleType['id'];
        // поле аудита записи
        $tab->grid->logField = 'header';
        // плагины сетки
        $tab->grid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->grid->bodyCls = 'g-grid_background';
        // маршрут
        $tab->grid->router['route'] = Gm::alias('@route');

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('{header}'),
            ['div', '{textShort}', ['align' => 'justify']],
            ['fieldset',
                [
                    HtmlNav::fieldLabel(
                        ExtGrid::columnIcon('gm-articles__icon-caching', 'svg') . ' ' . $this->t('Caching'),
                        HtmlNav::tplChecked('caching==1')
                    ),
                    HtmlNav::fieldLabel(
                        ExtGrid::columnIcon('gm-articles__icon-hits', 'svg') . ' ' . $this->t('Number of hits'), '{hits}'
                    ),
                    HtmlNav::fieldLabel($this->t('Index number'), '{index}'),
                    HtmlNav::tplIf(
                        'languageName', 
                        HtmlNav::fieldLabel($this->t('Language'), '{languageName}')
                    ),
                    HtmlNav::tplIf(
                        'template', 
                        HtmlNav::fieldLabel($this->t('Template'), '{template}')
                    ),
                    HtmlNav::tplIf(
                        'template',
                        HtmlNav::fieldLabel($this->t('Template'), '{template}')
                    ),
                    HtmlNav::tplIf(
                        'pageTemplate',
                        HtmlNav::fieldLabel($this->t('Page template'), '{pageTemplate}')
                    ),
                    HtmlNav::fieldLabel($this->t('Slug type'), '{slugType}'),
                    HtmlNav::tplIf(
                        'slug',
                        HtmlNav::fieldLabel($this->t('Slug'), '{slug}')
                    )
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Indexing')),
                    HtmlNav::tplIf(
                        'metaRobots', 
                        HtmlNav::fieldLabel($this->t('for all robots'), '{metaRobots}')
                    ),
                    HtmlNav::tplIf(
                        'metaYandex', 
                        HtmlNav::fieldLabel($this->t('robot only "Yandex"'), '{metaYandex}')
                    ),
                    HtmlNav::tplIf(
                        'metaGoogle', 
                        HtmlNav::fieldLabel($this->t('robot only "Google"'), '{metaGoogle}')
                    ),
                    HtmlNav::tplIf(
                        'indexing', '', $this->t('the article is not indexed by robots')
                    )
                ]
            ],
            HtmlNav::tpl(
                [
                    HtmlNav::tag('fieldset',[
                        HtmlNav::legend($this->t('Category')),
                        HtmlNav::fieldLabel($this->t('Name'), '{categoryName}'),
                        HtmlNav::fieldLabel($this->t('URL path'), '{categorySlugPath}')
                    ]),
                    '<tpl else>', ''
                ],
                ['if' => 'categorySlugPath']
            ),
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Date of publication of the article')),
                    HtmlNav::fieldLabel($this->t('Date'), '{publishDate:date("' . $formats['dateFormat'] . '")}'),
                    HtmlNav::fieldLabel($this->t('Time'), '{publishDate:date("' . $formats['timeFormat'] . '")}'),
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Publishing an article')),
                    HtmlNav::fieldLabel(
                        $this->t('on the main page of the site'), HtmlNav::tplChecked('publishOnMain==1')
                    ),
                    HtmlNav::fieldLabel(
                        $this->t('in site sections'), HtmlNav::tplChecked('publishInCategories==1')
                    ),
                    HtmlNav::fieldLabel(
                        $this->t('on the site'), HtmlNav::tplChecked('publish==1')
                    ),
                    HtmlNav::widgetButton(
                        $this->t('Edit article'),
                        ['route' => Gm::alias('@match', '/form/view/{id}?type={type}'), 'long' => true],
                        ['title' => $this->t('Edit article')]
                    ),
                    HtmlNav::linkButton(
                        $this->t('View article'),
                        ['long' => true],
                        ['title' => $this->t('View article'), 'href' => '{url}', 'target' => '_blank']
                    )
                ]
            ]
        ]);

        $tab
            ->addCss('/grid.css')
            ->addRequire('Gm.view.grid.column.Switch');
        return $tab;
    }
}
