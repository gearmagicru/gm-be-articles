<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Model;

use Gm;
use URLify;
use Gm\Helper\Json;
use Gm\Site\Data\Model\Article;
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля статьи сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Model
 * @since 1.0
 */
class Form extends FormModel
{
    /**
     * Тип материала.
     * 
     * @var null|array
     */
    public ?array $articleType = null;

    /**
     * @inheritdoc
     */
    protected bool $checkDirtyAttributes = false;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => true,
            'tableName'  => '{{article}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                [ // тип материала
                    'type_id',
                    'alias' => 'type', 
                    'label' => 'Article type'
                ],
                [ // язык
                    'language_id',
                    'alias' => 'language', 
                    'label' => 'Language'
                ],
                [ // шаблон статьи
                    'template', 
                    'label' => 'Template'
                ],
                [ // шаблон страницы
                    'page_template', 
                    'alias' => 'pageTemplate', 
                    'label' => 'Page template'
                ], 
                [ // категория статьи
                    'category_id', 
                    'alias' => 'category', 
                    'label' => 'Category'
                ], 
                [ // ярлык
                    'slug', 
                    'label' => 'Slug type'
                ],
                [ // хэш ярлыка
                    'slug_hash', 
                    'alias' => 'slugHash'
                ],
                [ // вид ярлыка: статический, динамический, основной
                    'slug_type', 
                    'alias' => 'slugType', 
                    'label' => 'Slug'
                ], 
                [ // заголовок статьи
                    'header', 
                    'label' => 'Header'
                ], 
                [ // изображение
                    'image', 
                    'label' => 'Image'
                ],
                [ // в тексте есть шорткод
                    'has_shortcode',
                    'alias' => 'hasShortcode'
                ],
                [ // дата публикации статьи
                    'publish_date', 
                    'alias' => 'publishDate', 
                    'label' => 'Date of publication of the post'
                ],
                [ // текст статьи
                    'text', 
                    'label' => 'Text'
                ],
                [ // текст статьи без форматирования
                    'text_plain', 
                    'alias' => 'textPlain'
                ],
                [ // анонс статьи
                    'announce', 
                    'label' => 'Announce'
                ],
                [ // анонс статьи без форматирования
                    'announce_plain', 
                    'alias' => 'announcePlain'
                ],
                [ // опубликовать статью на главной странице
                    'publish_on_main', 
                    'alias' => 'publishOnMain', 
                    'label' => 'on the main page of the site'
                ],
                [ // опубликовать статью в разделе сайта
                    'publish_in_categories', 
                    'alias' => 'publishInCategories', 
                    'label' => 'in site sections'
                ],
                [ // опубликовать статью на сайта
                    'publish', 
                    'alias' => 'publish', 
                    'label' => 'on the site'
                ],
                [ // название заголовка во вкладке браузера
                    'title', 
                    'label' => 'Title'
                ], 
                [ // ключевые слова
                    'meta_keywords', 
                    'alias' => 'keywords', 
                    'label' => 'Keywords'
                ], 
                [ // описание
                    'meta_description', 
                    'alias' => 'description', 
                    'label' => 'Description'
                ],
                // Директивы индексирования и показа контента:
                ['meta_robots', 'alias' => 'metaRobots'], // метатег robots
                ['meta_googlebot', 'alias' => 'metaGoogle'], // метатег googlebot
                ['meta_yandex', 'alias' => 'metaYandex'], // метатег yandex
                ['meta_tags', 'alias' => 'metaTags'], // все остальные метатеги
                // Карта сайта (Sitemap) для поисковых систем:
                ['sitemap_enabled', 'alias' => 'sitemapEnabled'], // включает статью
                ['sitemap_priority', 'alias' => 'sitemapPriority'], // приоритетность
                ['sitemap_frequency', 'alias' => 'sitemapFrequency'], // частота изменения
                // Фид (лента новостей, анонсов статей в формате RSS, RDF, Atom):
                ['feed_enabled', 'alias' => 'feedEnabled'],
                // Дополнительно:
                ['media_paths', 'alias' => 'mediaPaths'], // пути медиапок метериала
                ['index', 'label' => 'Index'], // порядковый номер
                ['hits', 'label' => 'Hits'], // посещений
                ['in_search', 'alias' => 'inSearch'], // поиск
                ['in_sitemap', 'alias' => 'inSitemap'], // карта сайта
                ['caching', 'alias' => 'caching'], // заголовок
                ['fields'],
                ['tags']
            ],
            'resetIncrements' => ['{{article}}'],
            // правила форматирования полей
            'formatterRules' => [
                [['slug', 'header', 'image', 'title', 'keywords', 'description'], 'safe'],
                [['publishOnMain', 'publishInCategories', 'publish', 'sitemapEnabled', 'feedEnabled', 
                  'inSearch', 'inSitemap', 'caching'], 'logic']
            ],
            // правила валидации полей
            'validationRules' => [
                [['template', 'pageTemplate', 'slugType', 'header'], 'notEmpty'],
                // ярлык
                [
                    'slug',
                    'between',
                    'max' => 255, 'type' => 'string'
                ],
                // заголовок
                [
                    'header',
                    'between',
                    'max' => 255, 'type' => 'string'
                ],
                // изображение
                [
                    'image',
                    'between',
                    'max' => 255, 'type' => 'string'
                ],
                // порядковый номер
                [
                    'index', 
                    'between',
                    'min' => 1, 'max' => PHP_INT_MAX
                ],
                // количество посещений
                [
                    'index', 
                    'between',
                    'min' => 0, 'max' => PHP_INT_MAX
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_SAVE, function ($isInsert, &$canSave) {
                if ($this->text) {
                    $shTags = Gm::$app->shortcodes->getShortcodeTagsInContent($this->text);
                    $this->hasShortcode = sizeof($shTags) > 0 ? 1 : 0; 
                } else
                    $this->hasShortcode = 0;
            })
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid('grid-' . $this->articleType['id']);
            })
            ->on(self::EVENT_BEFORE_DELETE, function (&$canDelete) {
                // удалить вложения
                if (!$this->deleteAttachments()) {
                    $canDelete = false;
                    // всплывающие сообщение
                    $this->response()
                        ->meta
                            ->cmdPopupMsg(
                                $this->t('Unable to delete attachments (files) of the material'), 
                                Gm::t(BACKEND, 'Deletion'), 
                                'error'
                            );
                    return;
                }
                // удалить все метки материала
                $this->deleteTags();
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid('grid-' . $this->articleType['id']);
            });
    }

    /**
     * Удаляет вложения (файлы) указанных материалов.
     * 
     * @return bool
     */
    protected function deleteAttachments(): bool
    {
        $article = new Article();
        $article->bindAttributes($this);
        return $article->deleteAttachment(true) !== false;
    }

    /**
     * Удаляет все метки материала.
     * 
     * @return void
     */
    protected function deleteTags(): bool
    {
        /** @var int|null $termId Идент. термина */
        $termId = Gm::getTermId('article');
        return $termId ? (Gm::$app->tagger->deleteTermTagsBy($this->id, null, $termId) !== false) : false;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeLoad(array &$data): void
    {
        // метки, чтобы не было конфликта между JSON и перечислением
        if (!empty($data['tags'])) {
            $tagsId = Json::decode($data['tags']);
            if ($tagsId === false) {
                $tagsId = (array) $this->tags;
            }
            $data['tags'] = implode(',', $tagsId);
        }

        // дата и время публикации
        if (empty($data['publishDate']) && empty($data['publishTime'])) {
            $data['publishDate'] = null;
        } else {
            $data['publishDate'] = [
                $data['publishDate'] ?? '', 
                $data['publishTime'] ?? ''
            ];
        }

        // обязательно указываем, чтобы имя атрибута было в методе `load`
        // т.к. необходим вызов метода unSlugHash
        $data['slugHash'] = '';

        // обязательно указываем, чтобы имя атрибута было в методе `load` 
        // т.к. поле может быть disabled
        $data['metaGoogle'] = $data['metaGoogle'] ?? null;
        $data['metaYandex'] = $data['metaYandex'] ?? null;
        $data['metaRobots'] = $data['metaRobots'] ?? null;

        // определяем поля материала
        $fields = [];
        $articleFields = $this->articleType['fields'] ?? [];
        foreach ($articleFields as $field) {
            if (isset($data[$field])) {
                $fields[$field] = $data[$field];
                unset($data[$field]);
            }
        }
        $data['fields'] = $fields;
    }

    /**
     * Возвращает формат директив  метатега "robots".
     * 
     * @return array
     */
    protected function getMetaRobotsFormat(): array
    {
        return [
            'noarchive'         => ['type' => 'bool'],
            'noyaca'            => ['type' => 'bool'],
            'noydir'            => ['type' => 'bool'],
            'noodp'             => ['type' => 'bool'],
            'notranslate'       => ['type' => 'bool'],
            'nopagereadaloud'   => ['type' => 'bool'],
            'noimageindex'      => ['type' => 'bool'],
            'nosnippet'         => ['type' => 'bool'],
            'unavailable_after' => ['type' => 'date'],
            'max-snippet'       => ['type' => 'string'],
            'max-image-preview' => ['type' => 'string'],
            'max-video-preview' => ['type' => 'int']
        ];
    }

    /**
     * Возвращает директивы атрибута "content" метатега "robots".
     * 
     * @param array $directives Директивы в виде пар "ключ - значение".
     * @param array $format Формат директив {@see Form::getMetaRobotsFormat()}.
     * 
     * @return array
     */
    protected function getMetaRobotsContent(array $directives, array $format): array
    {
        $result = [];
        // если массив нумерованный
        if (array_is_list($directives)) {
            foreach ($directives as $directive) {
                // если деректива имеет значение
                if (mb_strpos($directive, ':') !== false) {
                    $parts = explode(':', $directive);
                    $name = $parts[0];
                } else
                    $name = $directive;

                if (isset($format[$name])) {
                    $result[] = $directive;
                }
            }
        // если массив ассоциативный
        } else {
            foreach ($directives as $directive => $value) {
                if (!isset($format[$directive])) continue;
    
                $type  = $format[$directive]['type'];
                $value = trim($value);
                if ($type === 'date')
                    $value = $value ? $directive . ':' . Gm::$app->formatter->toDate($value, 'Y-m-d', false, Gm::$app->dataTimeZone) : null;
                else
                if ($type === 'bool')
                    $value = $value > 0 ? $directive : null;
                else {
                    settype($value, $type);
                    $value = $value ? $directive . ':' . $value : null;
                }
                if ($value)
                    $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * Устанавливает значение атрибуту "category".
     * 
     * @param null|string|int $value
     * 
     * @return void
     */
    public function setCategory($value)
    {
        $value = (int) $value;
        $this->attributes['category'] = $value === 0 ? null : $value;
    }

    /**
     * Возвращает значение атрибута "category" элементу интерфейса формы.
     * 
     * @param null|string|int $value
     * 
     * @return array
     */
    public function outCategory($value): array
    {
        /** @var \Gm\Backend\ArticleCategories\Model\Category $category */
        $category = $value ? Gm::$app->modules->getModel('Category', 'gm.be.article_categories') : null;
        $category = $category ? $category->selectByPk($value) : null;
        if ($category) {
            return [
                'type'  => 'combobox', 
                'value' => $category->id, 
                'text'  => $category->name
            ];
        }
        return [
            'type'  => 'combobox',
            'value' => 0,
            'text'  => Gm::t(BACKEND, '[None]')
        ]; 
    }

    /**
     * Устанавливает значение атрибуту "language".
     * 
     * @param null|string|int $value
     * 
     * @return void
     */
    public function setLanguage($value)
    {
        $value = (int) $value;
        $this->attributes['language'] = $value === 0 ? null : $value;
    }

    /**
     * Возвращает значение атрибута "language" для элемента интерфейса формы.
     * 
     * @param null|string|int $value
     * 
     * @return array
     */
    public function outLanguage($value): array
    {
        $language = $value ? Gm::$app->language->available->getBy($value, 'code') : null;
        if ($language) {
            return [
                'type'  => 'combobox', 
                'value' => $language['code'],
                'text'  => $language['shortName'] . ' (' . $language['tag'] . ')'
            ];
        }
        return [
            'type'  => 'combobox',
            'value' => 0,
            'text'  => Gm::t(BACKEND, '[None]')
        ];       
    }

    /**
     * Устанавливает значение атрибуту "fields".
     * 
     * @param null|string|array $value
     * 
     * @return void
     */
    public function setFields($value)
    {
        if ($value) {
            if (is_string($value)) {
                $value = Json::tryDecode($value);
            }
        } else
            $value = [];
        $result = [];
        $fields = $this->articleType['fields'] ?? [];
        foreach ($fields as $field) {
            $result[$field] = $value[$field] ?? '';
        }
        $this->attributes['fields'] = $result;
    }

    /**
     * Возвращает значение атрибута "fields" элементу интерфейса формы.
     * 
     * @param null|array $value
     * 
     * @return array
     */
    public function outFields($value): ?array
    {
        if ($value) {
            foreach ($value as $field => $fieldValue) {
                //$this->attributes["fields[$field]"] = $fieldValue;
                $this->attributes[$field] = $fieldValue;
            }
        }
        return $value;
    }

    /**
     * Возращает значение для сохранения в поле "fields".
     * 
     * @return string
     */
    public function unFields(): string
    {
        return json_encode((array) $this->fields);
    }

    /**
     * Устанавливает значение атрибуту "slugType".
     * 
     * @param string|int $value
     * 
     * @return void
     */
    public function setSlugType($value)
    {
        $this->attributes['slugType'] = (int) $value;
    }

    /**
     * Возращает значение для сохранения в поле "slug".
     * 
     * @return string
     */
    public function unSlug(): ?string
    {
        $value = $this->slug;

        // если вид ярлыка - материал на главной странице сайта или категории (раздела)
        if ($this->slugType == Article::SLUG_HOME)
            $value = null;
        else {
            $value = $value ?: $this->header;
            $value = $value ? URLify::filter($value, 255, '', true) : null;
        }
        return $this->slug = $value;
    }

    /**
     * Возращает значение для сохранения в поле "slug_hash".
     * 
     * @see Form::beforeLoad()
     * 
     * @return string
     */
    public function unSlugHash(): ?string
    {
        return $this->slug ? md5($this->slug) : null;
    }

    /**
     * Устанавливает значение атрибутам: "announce", "announcePlain".
     * 
     * @param null|string $value
     * 
     * @return void
     */
    public function setAnnounce($value)
    {
        $this->attributes['announce'] = $value;
        $this->attributes['announcePlain'] = $value ? strip_tags($value) : null;
    }

    /**
     * Устанавливает значение атрибутам: "text", "textPlain".
     * 
     * @param null|string $value
     * 
     * @return void
     */
    public function setText($value)
    {
        $this->attributes['text'] = $value;
        $this->attributes['textPlain'] = $value ? strip_tags($value) : null;
    }

    /**
     * Устанавливает значение атрибуту "tags".
     * 
     * @param null|string $value
     * 
     * @return void
     */
    public function setTags($value)
    {
        $this->attributes['tags'] = $value;
    }

    /**
     * Возращает значение для сохранения в поле "tags".
     * 
     * Т.к. поле меток передаёт значение в формате JSON, то атрибут `tags` может
     * иметь вид: '["tag_id1", "tag_id2"...]'.
     * 
     * Но сохраняем идент. меток через разделитель ',', т.к. поле меток автоматически
     * делает запрос указав значения идент. меток через разделитель.
     * 
     * @return string
     */
    public function unTags(): string
    {
        /** @var bool $isNewRecord */
        $isNewRecord = $this->isNewRecord();
        // если добавление, а метки не указаны
        if ($isNewRecord && empty($this->tags)) {
            return '';
        }

        // если изменились метки в поле
        if ($this->isDirtyAttribute('tags')) {
            $tagsId = $this->tags ? explode(',', $this->tags) : [];

            /** @var int|null $termId Идент. термина материала */
            $termId = Gm::getTermId('article');
            if ($termId === null) {
                return $this->tags;
            }

            /** @var int $rowId Идент. материала */
            $rowId = $this->isNewRecord() ? $this->getNextId() : $this->id;
            Gm::$app->tagger->saveTermTags($tagsId, $rowId, $termId, $this->articleType['id']);
        }
        return $this->tags;
    }

    /**
     * Возращает значение для сохранения в поле "tags".
     * 
     * @return string
     */
    public function outTags(): string
    {
        return empty($this->tags) ? '' : $this->tags;
    }

    /**
     * Устанавливает значение атрибуту "metaTags".
     * 
     * @param null|array|string $value Если значение `string`, то формат JSON.
     * 
     * @return void
     */
    public function setMetaTags($value)
    {
        $format = $this->getMetaRobotsFormat();
        if ($value) {
            if (is_string($value)) {
                $value = Json::tryDecode($value);
            }

            $value = [
                'robots' => $this->getMetaRobotsContent(
                    $value['robots'] ?? [],
                    $format
                )
            ];
        } else
            $value = [];
        $this->attributes['metaTags'] = $value;
    }

    /**
     * Возращает значение для сохранения в поле "meta_tags".
     * 
     * @return string
     */
    public function unMetaTags(): string
    {
        return json_encode((array) $this->metaTags);
    }

    /**
     * Возращает значение для сохранения в поле "meta_paths".
     * 
     * @return string
     */
    public function unMediaPaths(): string
    {
        if ($this->mediaPaths) {
            if (is_array($this->mediaPaths))
                return Json::encode($this->mediaPaths);
        }
        return '';
    }

    /**
     * Возвращает значение атрибута "metaTags" элементу интерфейса формы.
     * 
     * @param null|array $value
     * 
     * @return null|array
     */
    public function outMetaTags(?array $value): ?array
    {
        if ($value) {
            foreach ($value as $name => $directives) {
                $prefix = "metaTags[$name][";
                foreach ($directives as $directive) {
                    // если деректива не имеет значение
                    if (mb_strpos($directive, ':') === false) {
                        $this->attributes[$prefix . $directive . ']'] = 1;
                    } else {
                        $parts = explode(':', $directive);
                        $this->attributes[$prefix . $parts[0] . ']'] = $parts[1];
                    }
                }
            }
        }
        return null;
    }

    /**
     * Устанавливает значение атрибуту "metaRobots".
     * 
     * @param null|string $value
     * 
     * @return void
     */
    public function setMetaRobots($value)
    {
        $this->attributes['metaRobots'] = $value ?: null;
    }

    /**
     * Возвращает значение атрибута "metaRobots" элементу интерфейса формы.
     * 
     * @param null|string $value
     * 
     * @return null|string
     */
    public function outMetaRobots(?string $value): ?string
    {
        $this->attributes['metaRobotsIndexing'] = $value ? 1 : 0;
        return $value;
    }

    /**
     * Устанавливает значение атрибуту "metaYandex".
     * 
     * @param null|string $value
     * 
     * @return void
     */
    public function setMetaYandex($value)
    {
        $this->attributes['metaYandex'] = $value ?: null;
    }

    /**
     * Возвращает значение атрибута "metaYandex" элементу интерфейса формы.
     * 
     * @param null|string $value
     * 
     * @return null|string
     */
    public function outMetaYandex(?string $value): ?string
    {
        $this->attributes['metaYandexIndexing'] = $value ? 1 : 0;
        return $value;
    }

    /**
     * Устанавливает значение атрибуту "metaGoogle".
     * 
     * @param null|string $value
     * 
     * @return void
     */
    public function setMetaGoogle($value)
    {
        $this->attributes['metaGoogle'] = $value ?: null;
    }

    /**
     * Возвращает значение атрибута "metaGoogle" элементу интерфейса формы.
     * 
     * @param null|string $value
     * 
     * @return null|string
     */
    public function outMetaGoogle(?string $value): ?string
    {
        $this->attributes['metaGoogleIndexing'] = $value ? 1 : 0;
        return $value;
    }

    /**
     * Возвращает значение атрибута "publishDate" элементу интерфейса формы.
     * 
     * @return null|array
     */
    protected function unPublishDate(): ?string
    {
        $value = $this->publishDate;

        /** @var \Gm\Db\Adapter\Platform\AbstractPlatform $platform */
        $platform = Gm::$app->db->getPlatform();
        /** @var \Gm\I18n\Formatter $formatter Форматер */
        $formatter = Gm::$app->formatter;
        /** @var \DateTimeZone $dataTZ Часовой пояс на сервере хранилища*/
        $dataTZ = Gm::$app->dataTimeZone;
        /** @var \DateTimeZone $userTZ Часовой пояс пользователя */
        $userTZ = Gm::$app->user->getTimeZone();

        $date = $time = '';
        if ($value) {
            if (is_string($value)) {
                $value = explode(' ', $value);
            }
            if (is_array($value)) {
                $date = $value[0] ?? '';
                $time = $value[1] ?? '';
            }
        }

        // если указана дата публикации
        if ($date) {
            if ($time)
                $date = $date . ' ' . $time;
            else
                $date = $date . ' ' . $formatter->toTime('now', $platform->timeFormat, false, $userTZ);
        // если не указана дата публикации
        } else {
            if ($time)
                $date = $formatter->toDate('now', $platform->dateFormat, false, $userTZ) . ' ' . $time;
            else
                $date = $formatter->toDateTime('now', $platform->dateTimeFormat, false, $userTZ);
        }
        // переводим из часового пояса пользователя в часовой пояс сервера
        return $formatter->toDateTimeZone($date, $platform->dateTimeFormat, false, $userTZ, $dataTZ);
    }

    /**
     * Возвращает значение атрибутов: "publishDate", "publishTime" для элементов 
     * интерфейса формы.
     * 
     * @param null|string $value
     * 
     * @return null|string
     */
    public function outPublishDate($value): ?string
    {
        if ($value) {
            // преобразование из UTC в текущий часовой пояс
            $datetime = Gm::$app->formatter->toDateTime($value, 'php:Y-m-d_H:i:s', true, Gm::$app->user->getTimeZone());
            $datetime = explode('_', $datetime);
            $value = $datetime[0];
            $this->attributes['publishTime'] = $datetime[1];
        }
        return $value;
    }
}
