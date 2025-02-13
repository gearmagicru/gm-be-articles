<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru/framework/
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Widget;

use Gm;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Widget\EditWindow;
use Gm\Backend\Articles\Model\Article;

/**
 * Виджет окна материала.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Widget
 * @since 1.0
 */
class ArticleWindow extends EditWindow
{
    /**
     * {@inheritdoc}
     */
    public array $css = ['/form.css'];

    /**
     * Параметры виджета редактора материала (статьи).
     * 
     * @see ArticleWindow::setEditor()
     * 
     * @var array
     */
    protected array $editor = [];

    /**
     * Параметры виджета редактора анонса.
     * 
     * @see ArticleWindow::setAnnEditor()
     * 
     * @var array
     */
    protected array $annEditor = [];

    /**
     * Идентификатор типа материала.
     * 
     * @see ArticleWindow::setTypeId()
     * 
     * @var int
     */
    protected int $typeId = 0;

    /**
     * Конфигурация вкладок.
     * 
     * @see ArticleWindow::setTabs()
     * 
     * @var array
     */
    protected array $tabsConfig = [];

    /**
     * Материал (статья) сайта.
     * 
     * @see ArticleWindow::$passParams
     * 
     * @var Article
     */
    protected Article $article;

    /**
     * {@inheritdoc}
     */
    public array $passParams = [
        'article'
    ];

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        // панель формы (Gm.view.form.Panel GmJS)
        $this->form->controller = 'gm-be-articles-form';

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $this->width = 820;
        $this->height = 735;
        $this->bodyPadding = 0;
        $this->layout = 'fit';
        $this->resizable = true;
        $this->maximizable = true;
        $this->responsiveConfig = [
            'height < 735' => ['height' => '99%'],
            'width < 820' => ['width' => '99%'],
        ];
        $this
            ->setNamespaceJS('Gm.be.articles')
            ->addRequire('Gm.be.articles.FormController');
    }

    /**
     * Возвращает вкладку "Атрибуты".
     * 
     * @return array|null
     */
    protected function getTabAttributes(): ?array
    {
        /** @var string $formId Идентификатор формы компонента (gm-articles-form) */
        $formId = $this->form->makeViewID();

        /** @var array $config Конфигурация вкладки */
        $config = $this->tabsConfig['attributes'] ?? [];
        if (empty($config)) return null;

        /** @var array $tabItems Компоненты вкладки */
        $tabItems = [];
        // выбор языка
        if (empty($config['language'])) {
            $tabItems[] = [
                'xtype'      => 'g-field-combobox',
                'fieldLabel' => '#Language',
                'tooltip'    => '#The language in which the article (article) will be displayed',
                'name'       => 'language',
                'labelAlign' => 'right',
                'labelWidth' => 150,
                'width'      => 450,
                'hiddenName' => 'language',
                'store'      => [
                    'fields' => ['id', 'name'],
                    'proxy'  => [
                        'type'        => 'ajax',
                        'url'         => ['@backend/languages/trigger/combo'],
                        'extraParams' => ['combo' => 'language'],
                        'reader'      => [
                            'type'         => 'array',
                            'rootProperty' => 'data'
                        ]
                    ]
                ],
                'displayField' => 'name',
                'valueField'   => 'id',
                'editable'     => false,
                'allowBlank'   => true
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'language',
                'value' => $config['language']
            ];
        }

        // выбор шаблона материала
        if (empty($config['template'])) {
            $tabItems[] = ExtCombo::themeViews(
                '#Template', 
                'template', 
                FRONTEND, 
                ['type' => 'article'],
                [],
                [
                    'labelWidth' => 150,
                    'width'      => 450,
                    'tooltip'    => '#Article display template',
                    'allowBlank' => false
                ]
            );
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'template',
                'value' => $config['template']
            ];
        }

        // выбор шаблона страницы
        if (empty($config['pageTemplate'])) {
            $tabItems[] = ExtCombo::themeViews(
                '#Page template', 
                'pageTemplate', 
                FRONTEND, 
                ['type' => 'page'],
                [],
                [
                    'labelWidth' => 150,
                    'width'      => 450,
                    'tooltip'    => '#Article display template',
                    'allowBlank' => false
                ]
            );
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'pageTemplate',
                'value' => $config['pageTemplate']
            ];
        }

        // выбор вида ярлыка
        if (empty($config['slugType'])) {
            $tabItems[] =  [
                'xtype'      => 'combobox',
                'fieldLabel' => '#Slug type',
                'tooltip'    => '#The type of the slug determines the rules for the formation of the URL of the article',
                'name'       => 'slugType',
                'value'      => 2,
                'store'      => [
                    'fields' => ['value', 'type'],
                    'data'   => [
                        ['1', '#Static'], ['2', '#Dynamic'], ['3', '#Main']
                    ]
                ],
                'displayField' => 'type',
                'valueField'   => 'value',
                'queryMode'    => 'local',
                'editable'     => false,
                'allowBlank'   => false,
                'labelWidth'   => 150,
                'labelAlign'   => 'right',
                'width'        => 450,
                //'listeners'    => ['change' => 'onChangeSlugType']
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'slugType',
                'value' => $config['slugType']
            ];
        }

        // ярлык
        if (!empty($config['slugEnabled'])) {
            $tabItems[] = [
                'xtype'      => 'textfield',
                'labelAlign' => 'right',
                'labelWidth' => 150,
                'anchor'     => '100%',
                'maxLength'  => 255,
                'fieldLabel' => '#Slug',
                'tooltip'    => '#The Slug is a version of a name, a unique part of a URL. These are all lowercase letters and only Latin letters, numbers and hyphens.',
                'id'         => $formId . '__slug',
                'name'       => 'slug',
                'value'      => '',
                'allowBlank' => true
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'slug',
                'value' => ''
            ];
        }

        // выбор категории материала
        if (empty($config['category'])) {
            $tabItems[] = [
                'xtype'      => 'g-field-combobox',
                'fieldLabel' => '#Category',
                'tooltip'    => '#Category of article, the section in which the article will be located',
                'id'         => $formId . '__article-category',
                'name'       => 'category',
                'labelAlign' => 'right',
                'labelWidth' => 150,
                'width'      => '100%',
                'hiddenName' => 'category',
                'store'      => [
                    'fields' => ['id', 'name'],
                    'proxy'  => [
                        'type'        => 'ajax',
                        'url'         => ['@backend/article-categories/trigger/combo'],
                        'extraParams' => ['combo' =>  'categories'],
                        'reader' =>  [
                            'type'         => 'array',
                            'rootProperty' => 'data'
                        ]
                    ]
                ],
                'pageSize'     => 25,
                'displayField' => 'name',
                'valueField'   => 'id',
                'minChars'     => 3,
                'queryParam'   => 'q',
                'queryMode'    => 'remote',
                'editable'     => true,
                'allowBlank'   => true
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'category',
                'value' => $config['category']
            ];
        }
    
        // заголовок
        $tabItems[] = [
            'xtype'      => 'textfield',
            'anchor'     => '100%',
            'maxLength'  => 255,
            'fieldLabel' => '#Header',
            'tooltip'    => '#Article header',
            'name'       => 'header',
            'value'      => '#Article header',
            'style'      => 'margin-top:15px',
            'allowBlank' => false
        ];

        // основное изображение
        if (!empty($config['imageEnabled'])) {
            $tabItems[] = [
                'id'         => $this->creator->viewId('form__image'),
                'xtype'      => 'textfield',
                'anchor'     => '100%',
                'maxLength'  => 255,
                'fieldLabel' => '#Image',
                'tooltip'    => '#Preview image, displayed along with the title and lead of the article',
                'name'       => 'image',
                'triggers'   => [
                    'browse' => [
                        'cls'         => 'g-form__field-trigger g-form__field-trigger_browse',
                        'handler'     => 'onTriggerWidget',
                        'handlerArgs' => [
                            'route'  => Gm::alias('@backend', '/mediafiles/dialog'),
                            'params' => [
                                // идент. поля, которое получит результат
                                'applyTo' => $this->creator->viewId('form__image'), 
                                // псевдоним диалога
                                'alias' => 'article-image',
                                // короткий путь к вызываемой папке дилога
                                'mediaPath' => $this->article->getMediaPathByDialog('article-image'),
                            ]
                        ]
                    ]
                ],
                'allowBlank' => true
            ];
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'mediaPaths[article-image]',
                'value' => $this->article->getMediaPathByDialog('article-image')
            ];
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'mediaPaths[article-doc]',
                'value' => $this->article->getMediaPathByDialog('article-image')
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'image',
                'value' => ''
            ];
        }

        // дата публикации
        if (!empty($config['publishDateEnabled'])) {
            $tabItems[] = [
                'xtype' => 'fieldset',
                'title' => '#Date of publication of the article',
                'style' => 'margin-top:15px',
                'items' => [
                    [
                        'xtype'  => 'fieldcontainer',
                        'layout' => 'hbox',
                        'items'  => [
                            [
                                'xtype'      => 'datefield',
                                'fieldLabel' => '#Date',
                                'labelAlign' => 'right',
                                'labelWidth' => 50,
                                'width'      => 155,
                                'name'       => 'publishDate',
                                'format'     => 'd-m-Y',
                                'allowBlank' => true
                            ],
                            [
                                'xtype'      => 'timefield',
                                'name'       => 'publishTime',
                                'fieldLabel' => '#Time',
                                'labelAlign' => 'right',
                                'labelWidth' => 50,
                                'width'      =>  150,
                                'format'     =>  'H:i:s',
                                'allowBlank' =>  true
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'publishDate',
                'value' => ''
            ];
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'publishTime',
                'value' => ''
            ];
        }

        // если отображаются флажки публикации: на главной странице, в разделах сайта, на сайте
        if (!empty($config['publishOnMain']) || !empty($config['publishInCategories']) || !empty($config['publish'])) {
            $tabItems[] = [
                'xtype' => 'label',
                'ui'    => 'header-line',
                'text'  => '#Publish article:'
            ];
        }
    
        // если отображаются флажок: на главной странице
        if (!empty($config['publishOnMain'])) {
            $tabItems[] = [
                'xtype'      => 'checkbox',
                'fieldLabel' => '#on the main page of the site',
                'name'       => 'publishOnMain',
                'inputValue' => 1,
                'labelAlign' => 'right',
                'labelWidth' => 180,
                'checked'    => true,
                'ui'         => 'switch',
                'inputValue' => 1
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'publishOnMain',
                'value' => 1
            ];
        }
    
        // если отображаются флажок: в разделах сайта
        if (!empty($config['publishInCategories'])) {
            $tabItems[] = [
                'xtype'      => 'checkbox',
                'fieldLabel' => '#in site sections',
                'name'       => 'publishInCategories',
                'inputValue' => 1,
                'labelAlign' => 'right',
                'labelWidth' => 180,
                'checked'    => true,
                'ui'         => 'switch',
                
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'publishInCategories',
                'value' => 1
            ];
        }
    
        // если отображаются флажок: на сайте
        if (!empty($config['publish'])) {
            $tabItems[] = [
                'xtype'      => 'checkbox',
                'fieldLabel' => '#on the site',
                'name'       => 'publish',
                'inputValue' => 1,
                'labelAlign' => 'right',
                'labelWidth' => 180,
                'checked'    => true,
                'ui'         => 'switch'
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'publish',
                'value' => 1
            ];
        }
        return [
            'title'       => empty($config['title']) ? '#Attributes' : $config['title'],
            'hidden'      => empty($config['visible']),
            'iconCls'     => 'g-icon-svg g-icon-svg_size_16 g-icon-m_color_tab gm-articles__icon-common',
            'bodyPadding' => 10,
            'autoScroll'  => true,
            'layout'      => 'anchor',
            'defaults'    => [
                'labelWidth' => 150,
                'labelAlign' => 'right'
            ],
            'items' => $tabItems
        ];
    }

    /**
     * Возвращает вкладку "Анонс".
     * 
     * @return array|null
     */
    protected function getTabAnnounce(): ?array
    {
        /** @var array $config Конфигурация вкладки */
        $config = $this->tabsConfig['announce'] ?? [];
        if (empty($config)) return null;

        return [
            'title'   => empty($config['title']) ? '#Announce' : $config['title'],
            'hidden'  => empty($config['visible']),
            'iconCls' => 'g-icon-svg g-icon-svg_size_16 g-icon-m_color_tab gm-articles__icon-text',
            'layout'  => 'fit',
            'items'   => [
                $this->applyAnnEditor('announce')
            ]
        ];
    }

    /**
     * Возвращает вкладку "Текст".
     * 
     * @return array|null
     */
    protected function getTabText(): ?array
    {
        /** @var array $config Конфигурация вкладки */
        $config = $this->tabsConfig['text'] ?? [];
        if (empty($config)) return null;

        return [
            'title'   => empty($config['title']) ? '#Text' : $config['title'],
            'hidden'  => empty($config['visible']),
            'iconCls' => 'g-icon-svg g-icon-svg_size_16 g-icon-m_color_tab gm-articles__icon-text',
            'layout'  => 'fit',
            'items'   => [
                $this->applyEditor('text')
            ]
        ];
    }

    /**
     * Возвращает вкладку "Дополнительно".
     * 
     * @return array|null
     */
    protected function getTabAdditionally(): ?array
    {
        /** @var array $config Конфигурация вкладки */
        $config = $this->tabsConfig['additionally'] ?? [];
        if (empty($config)) return null;

        $tabItems = [];

        // поле "Порядок"
        if (!empty($config['fldIndexShow'])) {
            $tabItems[] = [
                'xtype'      => 'textfield',
                'width'      => 250,
                'maxLength'  => 5,
                'fieldLabel' => '#Index',
                'tooltip'    => '#The ordinal number of the article is used to sort the articles in the list.',
                'name'       => 'index',
                'value'      => 1,
                'allowBlank' => true
            ];
        } else {
            // т.к. поле проходит проверку, то:
            $tabItems[] =  [
                'xtype' => 'hidden',
                'name'  => 'index',
                'value' => 1
            ];
        }

        // поле "Количество посещений"
        if (!empty($config['fldHitsShow'])) {
            $tabItems[] = [
                'xtype'      => 'textfield',
                'width'      => 250,
                'maxLength'  => 5,
                'fieldLabel' => '#Number of hits',
                'name'       => 'hits',
                'value'      => 0,
                'allowBlank' => true
            ];
        } else {
            // т.к. поле проходит проверку, то:
            $tabItems[] =  [
                'xtype' => 'hidden',
                'name'  => 'hits',
                'value' => 0
            ];
        }

        // поле "Метки"
        if (!empty($config['fldTagsShow'])) {
            $tabItems[] = [
                'xtype'      => 'tagfield',
                'fieldLabel' => '#Tags',
                'tooltip'    => '#Labels or tags that will be displayed on the content page as hyperlinks',
                'name'       => 'tags',
                'width'      => 400,
                'store'      => [
                    'fields' => ['id', 'name', 'img'],
                    'proxy'  => [
                        'type'        => 'ajax',
                        'url'         => ['@backend/tag-manager/trigger/combo'],
                        'extraParams' => ['combo' => 'tags', 'noneRow' => 0, 'side' => 0],
                        'reader'      => [
                            'type'         => 'array',
                            'rootProperty' => 'data'
                        ]
                    ]
                ],
                'encodeSubmitValue' => true,
                'displayField'      => 'name',
                'valueField'        => 'id',
                'createNewOnEnter'  => false,
                'createNewOnBlur'   => false,
                'filterPickList'    => true
            ];
        }

        // флажок "Поиск"
        if (!empty($config['fldInSearchShow'])) {
            $tabItems[] = [
                'xtype'      => 'checkbox',
                'fieldLabel' => '#<span>search</span>',
                'boxLabel'   => '#the article participates in the search on the site',
                'name'       => 'inSearch',
                'inputValue' => 1,
                'checked'    => true,
                'cls'        => 'gm-articles__label-info',
                'ui'         => 'switch'
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'inSearch',
                'value' => $config['fldInSearchCheck'] ?? 0
            ];
        }
    
        // флажок "Карта сайта"
        if (!empty($config['fldInSitemapShow'])) {
            $tabItems[] = [
                'xtype'      => 'checkbox',
                'fieldLabel' => '#<span>sitemap</span>',
                'boxLabel'   => '#the sitemap includes a link to the article',
                'name'       => 'inSitemap',
                'inputValue' => 1,
                'checked'    => true,
                'cls'        => 'gm-articles__label-info',
                'ui'         => 'switch'
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'inSitemap',
                'value' => $config['fldSitemapCheck'] ?? 0
            ];
        }

        // флажок "Кэширование"
        if (!empty($config['fldCachingShow'])) {
            $tabItems[] = [
                'xtype'      => 'checkbox',
                'fieldLabel' => '#<span>caching</span>',
                'name'       => 'caching',
                'inputValue' => 1,
                'cls'        => 'gm-articles__label-info',
                'ui'         => 'switch'
            ];
        } else {
            $tabItems[] = [
                'xtype' => 'hidden',
                'name'  => 'caching',
                'value' => $config['fldCachingCheck'] ?? 0
            ];
        }

        return [
            'title'       => empty($config['title']) ? '#Additionally' : $config['title'],
            'hidden'      => empty($config['visible']),
            'iconCls'     => 'g-icon-svg g-icon-svg_size_16 g-icon-m_color_tab gm-articles__icon-addition',
            'bodyPadding' => 10,
            'defaults'    => [
                'labelAlign' => 'right',
                'labelWidth' => 145
            ],
            'items' => $tabItems
        ];
    }

    /**
     * Возвращает вкладку "SEO".
     * 
     * @return array|null
     */
    protected function getTabSeo(): ?array
    {
        /** @var string $formId Идентификатор формы компонента (gm-articles-form) */
        $formId = $this->form->makeViewID();

        /** @var array $config Конфигурация вкладки */
        $config = $this->tabsConfig['seo'] ?? [];
        if (empty($config)) return null;

        /** @var array $tabItems Компоненты вкладки */
        $tabItems = [];

        // метатеги
        if (!empty($config['metatagEnabled'])) {
            $tabItems = array_merge($tabItems, [
                [
                    'xtype' => 'label',
                    'ui'    => 'header-line',
                    'text'  => '#Article meta tag (title, description, keywords)'
                ],
                [
                    'xtype'       => 'textfield',
                    'baseCls'     => 'g-form__field',
                    'fieldLabel'  => '#Name',
                    'labelWidth'  => 250,
                    'labelAlign'  => 'right',
                    'width'       => '100%',
                    'name'        => 'title',
                    'note'        => '#title of the title in the browser tab',
                    'maxLength'   => 255
                ],
                [
                    'xtype'      => 'textarea',
                    'baseCls'    => 'g-form__field',
                    'fieldLabel' => '#Keywords',
                    'labelWidth' => 250,
                    'labelAlign' => 'right',
                    'name'       => 'keywords',
                    'note'       => '#search engines use it to determine the relevance of a link',
                    'anchor'     => '100%',
                    'height'     => 100
                ],
                [
                    'xtype'      => 'textarea',
                    'baseCls'    => 'g-form__field',
                    'fieldLabel' => '#Description',
                    'labelWidth' => 250,
                    'labelAlign' => 'right',
                    'name'       => 'description',
                    'note'       => '#used by search engines for indexing, as well as when creating an annotation in the search results on demand',
                    'anchor'     => '100%',
                    'height'     => 100
                ]
            ]);
        }

        // карта сайта
        if (!empty($config['sitemapEnabled'])) {
            $tabItems = array_merge($tabItems, [
                [
                    'xtype' => 'label',
                    'ui'    => 'header-line',
                    'text'  => '#Sitemap for search engines'
                ],
                [
                    'xtype'      => 'checkbox',
                    'fieldLabel' => '#includes article',
                    'name'       => 'sitemapEnabled',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 160,
                    'ui'         => 'switch',
                    'checked'    => true
                ],
                [
                    'xtype'      => 'combobox',
                    'fieldLabel' => '#priority',
                    'tooltip'    => '#The priority of URLs relative to other URLs on your site',
                    'name'       => 'sitemapPriority',
                    'store'      => [
                        'fields' => ['priority'],
                        'data'   => [
                            ['0.0'],['0.1'],['0.2'],['0.3'],['0.4'],['0.5'],['0.6'],['0.7'],['0.8'],['0.9'],['1.0']
                        ]
                    ],
                    'displayField' => 'priority',
                    'valueField'   => 'priority',
                    'value'        => '0.5',
                    'queryMode'    => 'local',
                    'editable'     => false,
                    'labelWidth'   => 160,
                    'labelAlign'   => 'right',
                    'width'        => 290
                ],
                [
                    'xtype'      => 'combobox',
                    'fieldLabel' => '#rate of change',
                    'tooltip'    => '#The likely frequency of changes to this page',
                    'name'       => 'sitemapFrequency',
                    'store'      => [
                        'fields' => ['frequency', 'title'],
                        'data'   => [
                            ['always', '#always'],
                            ['hourly', '#hourly'],
                            ['daily', '#daily'],
                            ['weekly', '#weekly'],
                            ['monthly', '#monthly'],
                            ['yearly', '#yearly'],
                            ['never', '#never']
                        ]
                    ],
                    'displayField' => 'title',
                    'valueField'   => 'frequency',
                    'value'        => '',
                    'queryMode'    => 'local',
                    'editable'     => false,
                    'labelWidth'   => 160,
                    'labelAlign'   => 'right',
                    'width'        => 290
                ]
            ]);
        }

        // карта сайта
        if (!empty($config['feedEnabled'])) {
            $tabItems = array_merge($tabItems, [
                [
                    'xtype' => 'label',
                    'ui'    => 'header-line',
                    'text'  => '#Feed (news feed, article announcements in RSS, RDF, Atom format)'
                ],
                [
                    'xtype'      => 'checkbox',
                    'fieldLabel' => '#includes article',
                    'name'       => 'feedEnabled',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 160,
                    'ui'         => 'switch',
                    'checked'    => true
                ]
            ]);
        }

        // индексирование
        if (!empty($config['indexingEnabled'])) {
            $tabItems = array_merge($tabItems, [
                [
                    'xtype' => 'label',
                    'ui'    => 'header-line',
                    'text'  => '#Indexing and serving content directives'
                ],
                [
                    'xtype'  => 'fieldcontainer',
                    'layout' => 'hbox',
                    'items'  => [
                        [
                            'xtype'      => 'checkbox',
                            'fieldLabel' => '#for all robots',
                            'name'       => 'metaRobotsIndexing',
                            'inputValue' => 1,
                            'labelAlign' => 'right',
                            'labelWidth' => 160,
                            'ui'         => 'switch',
                            'listeners'  => ['change' => 'onChangeMetaRobots'],
                            'checked'    => true
                        ],
                        [
                            'xtype'      => 'combobox',
                            'fieldLabel' => '#metatag "robots"',
                            'name'       => 'metaRobots',
                            'id'         => $formId . '__metarobots',
                            'store'      => [
                                'fields' => ['content', 'description'],
                                'data'   => [
                                    ['all', 'all'],
                                    ['none', 'none'],
                                    ['index, follow', 'index, follow'],
                                    ['index, nofollow', 'index, nofollow'],
                                    ['noindex, nofollow', 'noindex, nofollow'],
                                    ['noindex', 'noindex'],
                                    ['nofollow', 'nofollow']
                                ]
                            ],
                            'displayField' => 'description',
                            'valueField'   => 'content',
                            'value'        => 'index, nofollow',
                            'queryMode'    => 'local',
                            'editable'     => false,
                            'labelWidth'   => 120,
                            'labelAlign'   => 'right',
                            'width'        => 320
                        ]
                    ]
                ],
                [
                    'xtype'  => 'fieldcontainer',
                    'layout' => 'hbox',
                    'items'  => [
                        [
                            'xtype'      => 'checkbox',
                            'fieldLabel' => '#robot only "Google"',
                            'name'       => 'metaGoogleIndexing',
                            'inputValue' => 1,
                            'labelAlign' => 'right',
                            'labelWidth' => 160,
                            'ui'         => 'switch',
                            'listeners'  => ['change' => 'onChangeMetaGoogle']
                        ],
                        [
                            'xtype'      => 'combobox',
                            'fieldLabel' => '#metatag "googlebot"',
                            'name'       => 'metaGoogle',
                            'disabled'   => true,
                            'id'         => $formId . '__metagoogle',
                            'store'      => [
                                'fields' => ['content', 'description'],
                                'data'   => [
                                    ['all', 'all'],
                                    ['none', 'none'],
                                    ['index, follow', 'index, follow'],
                                    ['index, nofollow', 'index, nofollow'],
                                    ['noindex, nofollow', 'noindex, nofollow'],
                                    ['noindex', 'noindex'],
                                    ['nofollow', 'nofollow']
                                ]
                            ],
                            'displayField' => 'description',
                            'valueField'   => 'content',
                            'queryMode'    => 'local',
                            'editable'     => false,
                            'labelWidth'   => 120,
                            'labelAlign'   => 'right',
                            'width'        => 320,
                        ]
                    ]
                ],
                [
                    'xtype'  => 'fieldcontainer',
                    'layout' => 'hbox',
                    'items'  => [
                        [
                            'xtype'      => 'checkbox',
                            'fieldLabel' => '#robot only "Yandex"',
                            'name'       => 'metaYandexIndexing',
                            'inputValue' => 1,
                            'labelAlign' => 'right',
                            'labelWidth' => 160,
                            'ui'         => 'switch',
                            'listeners'  => ['change' => 'onChangeMetaYandex']
                        ],
                        [
                            'xtype'      => 'combobox',
                            'fieldLabel' => '#metatag "yandex"',
                            'name'       => 'metaYandex',
                            'disabled'   => true,
                            'id'         => $formId . '__metayandex',
                            'store'      => [
                                'fields' => ['content', 'description'],
                                'data'   => [
                                    ['all', 'all'],
                                    ['none', 'none'],
                                    ['index, follow', 'index, follow'],
                                    ['index, nofollow', 'index, nofollow'],
                                    ['noindex, nofollow', 'noindex, nofollow'],
                                    ['noindex', 'noindex'],
                                    ['nofollow', 'nofollow']
                                ]
                            ],
                            'displayField' => 'description',
                            'valueField'   => 'content',
                            'queryMode'    => 'local',
                            'editable'     => false,
                            'labelWidth'   => 120,
                            'labelAlign'   => 'right',
                            'width'        => 320
                        ]
                    ]
                ],
                [
                    'xtype' => 'label',
                    'cls'   => 'gm-articles__label-info',
                    'html'  => '#all - no restrictions on indexing and displaying content, equivalent to index, follow;'
                ],
                [
                    'xtype' => 'label',
                    'cls'   => 'gm-articles__label-info',
                    'html'  => '#none - is equivalent to noindex, nofollow;'
                ],
                [
                    'xtype' => 'label',
                    'cls'   => 'gm-articles__label-info',
                    'html'  => '#index - allows robots to index the page;'
                ],
                [
                    'xtype' => 'label',
                    'cls'   => 'gm-articles__label-info',
                    'html'  => '#noindex - prevents robots from indexing the page;'
                ],
                [
                    'xtype' => 'label',
                    'cls'   => 'gm-articles__label-info',
                    'html'  => '#follow - allows robots to follow links on the page;'
                ],
                [
                    'xtype' => 'label',
                    'cls'   => 'gm-articles__label-info',
                    'html'  => '#nofollow - prevents robots from following links on the page.'
                ],
                [
                    'xtype' => 'label',
                    'cls'   => 'gm-articles__label-info',
                    'html'  => '#more about meta tags: Google, Yandex'
                ]
            ]);
        }

        // директивы
        if (!empty($config['directivesEnabled'])) {
            $tabItems = array_merge($tabItems, [
                [
                    'xtype' => 'label',
                    'ui'    => 'header-line',
                    'text'  => '#Special meta tag directives'
                ],
                [
                    'xtype'      => 'checkbox',
                    'boxLabel'   => '#do not show the link to the saved copy in the search results',
                    'fieldLabel' => '#directive "noarchive" of meta tag "robots"',
                    'name'       => 'metaTags[robots][noarchive]',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 240,
                    'cls'        => 'gm-articles__label-info',
                    'ui'         => 'switch'
                ],
                [
                    'xtype'      => 'checkbox',
                    'boxLabel'   => '#ban on using descriptions from Yandex',
                    'fieldLabel' => '#directive "noyaca" of meta tag "yandex"',
                    'name'       => 'metaTags[robots][noyaca]',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 240,
                    'cls'        => 'gm-articles__label-info',
                    'ui'         => 'switch'
                ],
                [
                    'xtype'      => 'checkbox',
                    'boxLabel'   => '#restricting the borrowing of descriptions and site names from the Yahoo directory',
                    'fieldLabel' => '#directive "noydir" of meta tag "robots"',
                    'name'       => 'metaTags[robots][noydir]',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 240,
                    'cls'        => 'gm-articles__label-info',
                    'ui'         => 'switch'
                ],
                [
                    'xtype'      => 'checkbox',
                    'boxLabel'   => '#limiting the borrowing of site descriptions and names from the Open Directory Project',
                    'fieldLabel' => '#directive "noodp" of meta tag "robots"',
                    'name'       => 'metaTags[robots][noodp]',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 240,
                    'cls'        => 'gm-articles__label-info',
                    'ui'         => 'switch'
                ],
                [
                    'xtype'      => 'checkbox',
                    'boxLabel'   => '#informs Google that this page should not be translated into other languages',
                    'fieldLabel' => '#directive "notranslate" of meta tag "google"',
                    'name'       => 'metaTags[robots][notranslate]',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 240,
                    'cls'        => 'gm-articles__label-info',
                    'ui'         => 'switch'
                ],
                [
                    'xtype'      => 'checkbox',
                    'boxLabel'   => '#prevents browsers from performing such Google Assistant voice commands',
                    'fieldLabel' => '#directive "nopagereadaloud" of meta tag "google"',
                    'name'       => 'metaTags[robots][nopagereadaloud]',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 240,
                    'cls'        => 'gm-articles__label-info',
                    'ui'         => 'switch'
                ],
                [
                    'xtype'      => 'checkbox',
                    'boxLabel'   => '#prohibits indexing images on the page',
                    'fieldLabel' => '#directive "noimageindex" of meta tag "robots"',
                    'name'       => 'metaTags[robots][noimageindex]',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 240,
                    'cls'        => 'gm-articles__label-info',
                    'ui'         => 'switch'
                ],
                [
                    'xtype'      => 'checkbox',
                    'boxLabel'   => '#do not show text or video in search results',
                    'fieldLabel' => '#directive "nosnippet" of meta tag "robots"',
                    'name'       => 'metaTags[robots][nosnippet]',
                    'inputValue' => 1,
                    'labelAlign' => 'right',
                    'labelWidth' => 240,
                    'cls'        => 'gm-articles__label-info',
                    'ui'         => 'switch'
                ],
                [
                    'xtype'      => 'displayfield',
                    'value'      => '#prevents the page from being shown in search results after the date and time',
                    'fieldLabel' => '#directive "unavailable_after" of meta tag "robots"',
                    'labelAlign' => 'right',
                    'labelWidth' => 285,
                    'cls'        => 'gm-articles__label-info'
                ],
                [
                    'xtype'      => 'datefield',
                    'fieldLabel' => '#date',
                    'labelAlign' => 'right',
                    'labelWidth' => 285,
                    'cls'        => 'gm-articles__label-info',
                    'width'      => 150,
                    'name'       => 'metaTags[robots][unavailable_after]',
                    'format'     => 'd-m-Y',
                    'allowBlank' => true
                ],
                [
                    'xtype'      => 'displayfield',
                    'value'      => '#limitation on the number of characters in a text fragment',
                    'fieldLabel' => '#directive "max-snippet" of meta tag "robots"',
                    'labelAlign' => 'right',
                    'labelWidth' => 285,
                    'cls'        => 'gm-articles__label-info'
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#value',
                    'labelAlign' => 'right',
                    'labelWidth' => 285,
                    'cls'        => 'gm-articles__label-info',
                    'width'      => 150,
                    'name'       => 'metaTags[robots][max-snippet]',
                    'allowBlank' => true
                ],
                [
                    'xtype'      => 'displayfield',
                    'value'      => '#directive defines the maximum size of images',
                    'fieldLabel' => '#directive "max-image-preview" of meta tag "robots"',
                    'labelAlign' => 'right',
                    'labelWidth' => 285,
                    'cls'        => 'gm-articles__label-info'
                ],
                [
                    'xtype'      => 'combobox',
                    'fieldLabel' => '#value',
                    'name'       => 'metaTags[robots][max-image-preview]',
                    'store'      => [
                        'fields' => ['content', 'description'],
                        'data'   => [
                            ['none ', '#no picture'],
                            ['standard', '#medium image'],
                            ['large ', '#large image']
                        ]
                    ],
                    'displayField' => 'description',
                    'valueField'   => 'content',
                    'queryMode'    => 'local',
                    'editable'     => true,
                    'labelWidth'   => 285,
                    'labelAlign'   => 'right',
                    'cls'          => 'gm-articles__label-info',
                    'width'        => 320
                ],
                [
                    'xtype'      => 'displayfield',
                    'value'      => '#sets a limit on the duration in seconds for a video fragment from a page',
                    'fieldLabel' => '#directive "max-video-preview" of meta tag "robots"',
                    'labelAlign' => 'right',
                    'labelWidth' => 285,
                    'cls'        => 'gm-articles__label-info',
                ],
                [
                    'xtype'      => 'textfield',
                    'fieldLabel' => '#value',
                    'labelAlign' => 'right',
                    'labelWidth' => 285,
                    'cls'        => 'gm-articles__label-info',
                    'width'      => 150,
                    'name'       => 'metaTags[robots][max-video-preview]',
                    'allowBlank' => true
                ]
            ]);
        }


        return [
            'title'       => empty($config['title']) ? '#SEO' : $config['title'],
            'hidden'      => empty($config['visible']),
            'iconCls'     => 'g-icon-svg g-icon-svg_size_16 g-icon-m_color_tab gm-articles__icon-seo',
            'bodyPadding' => 5,
            'autoScroll'  => true,
            'layout'      => 'anchor',
            'items'       => $tabItems
        ];
    }

    /**
     * Возвращает панель вкладок.
     * 
     * @return array
     */
    protected function getTabPanel(): array
    {
        return [
            'xtype'           => 'tabpanel',
            'activeTab'       => 0,
            'ui'              => 'flat-light',
            'enableTabScroll' => true,
            'anchor'          => '100% 100%',
            'autoRender'      => true,
            'items'           => [
                $this->getTabAttributes(),
                $this->getTabAnnounce(),
                $this->getTabText(),
                $this->getTabSeo(),
                $this->getTabAdditionally()
            ]
        ];
    }

    /**
     * Указывает редакатору поле отправки.
     * 
     * @param string $fieldName Имя поля.
     * 
     * @return array
     */
    protected function applyEditor(string $fieldName): array
    {
        if (empty($this->editor)) return [];

        $editor = $this->editor;
        $editor['name'] = $fieldName;
        return $editor;
    }

    /**
     * Устанавливает редактор статьи.
     * 
     * @param array $editor Редактор.
     * 
     * @return $this
     */
    public function setEditor(array $editor): static
    {
        if (empty($editor)) {
            $editor = [
                'xtype'  => 'textarea',
                'anchor' => '100% 100%'
            ];
        }
        $this->editor = $editor;
        return $this;
    }

    /**
     * Устанавливает редактор анонса.
     * 
     * @param array $editor Редактор.
     * 
     * @return $this
     */
    public function setAnnEditor(array $editor): static
    {
        if (empty($editor)) {
            $editor = [
                'xtype'  => 'textarea',
                'anchor' => '100% 100%'
            ];
        }
        $this->annEditor = $editor;
        return $this;
    }

    /**
     * Указывает редакатору анонса поле отправки.
     * 
     * @param string $fieldName Имя поля.
     * 
     * @return array
     */
    protected function applyAnnEditor(string $fieldName): array
    {
        if (empty($this->annEditor)) return [];

        $editor = $this->annEditor;
        $editor['name'] = $fieldName;
        return $editor;
    }

    /**
     * Устанавливает компоненты материала.
     * 
     * См. Справочники \ Типы материалов \ Интерфейс формы \ Элементы формы (компоненты)
     * 
     * @param array $components Компоненты (элементы формы типа метериала).
     * 
     * @return $this
     */
    public function setComponents(array $components): static
    {
        $this->components = $components;
        return $this;
    }

    /**
     * Устанавливает конфигурацию вкладкам.
     * 
     * @param array $tabsConfig Конфигурация вкладок.
     * 
     * @return $this
     */
    public function setTabs(array $tabsConfig): static
    {
        $this->tabsConfig = $tabsConfig;
        return $this;
    }

    /**
     * Устанавливает идентификатор материала.
     * 
     * @param array $id Идентификатор материала.
     * 
     * @return $this
     */
    public function setTypeId(int $id): static
    {
        $this->typeId = $id;
        $this->form->router->rules['delete'] = '{route}/delete/{id}?type=' .  $id;
        $this->form->router->rules['data']   = '{route}/data/{id}?type=' .  $id;
        return $this;
    }

    /**
     * Возвращает элементы формы окна.
     * 
     * @return array
     */
    public function renderFormItems(): array
    {
        $tabPanel = $this->getTabPanel();
        if ($this->components) {
            $tabPanel['items'] = array_merge($tabPanel['items'], $this->components);
        }
        return [
            $tabPanel,
            [
                'xtype' => 'hidden',
                'name'  => 'type',
                'value' => $this->typeId,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeRender(): bool
    {
        parent::beforeRender();

        $this->form->items = $this->renderFormItems();
        return true;
    }
}
