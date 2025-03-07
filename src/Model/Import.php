<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Model;

/**
 * Импорт данных.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Model
 * @since 1.0
 */
class Import extends \Gm\Import\Import
{
    /**
     * {@inheritdoc}
     */
    protected string $modelClass = '\Gm\Backend\Articles\Model\Article';

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            // идентификатор материала
            'type_id' => [
                'field' => 'type_id', 
                'type'  => 'int'
            ],
            // идентификатор категории материала
            'category_id' => [
                'field' => 'category_id', 
                'type'  => 'int'
            ],
            // идентификатор языка
            'language_id' => [
                'field' => 'language_id', 
                'type'  => 'int'
            ],
            // порядковый номер
            'index' => [
                'field' => 'index', 
                'type'  => 'int'
            ],
            // количество посещений
            'hits' => [
                'field' => 'hits', 
                'type'  => 'int'
            ],
            // фиксация материала в списке
            'fixed' => [
                'field' => 'fixed', 
                'type'  => 'int'
            ],
            // файл шаблона материала
            'template' => [
                'field'  => 'template',
                'length' => 255,
                'trim'   => true
            ],
            // файл шаблона страницы
            'page_template' => [
                'field'  => 'page_template',
                'length' => 255,
                'trim'   => true
            ],
            // пути к медиа ресурсам
            'media_paths' => [
                'field' => 'media_paths',
                'trim'  => true
            ],
            // указан в тексте шорткод
            'has_shortcode' => [
                'field' => 'has_shortcode', 
                'type'  => 'int'
            ],
            // опубликовать материал на сайте
            'publish' => [
                'field' => 'publish', 
                'type'  => 'int'
            ], 
            // опубликовать материал на главной странице
            'publish_on_main' => [
                'field' => 'publish_on_main', 
                'type'  => 'int'
            ],
            // опубликовать материал в разделах сайта
            'publish_in_categories' => [
                'field' => 'publish_in_categories', 
                'type' => 'int'
            ], 
            // дата и время публикации материала
            'publish_date' => [
                'field' => 'publish_date',
                'trim'  => true
            ],
            // заголовок материала
            'header' => [
                'field'  => 'header',
                'length' => 255,
                'trim'   => true
            ],
            // изображение материала
            'image' => [
                'field'  => 'image',
                'length' => 255,
                'trim'   => true
            ],
            // текст материала
            'text' => [
                'field' => 'text',
                'trim'  => true
            ],
            // анонс материала 
            'announce' => [
                'field' => 'announce',
                'trim'  => true
            ],
            // ярлык (слаг)
            'slug' => [
                'field'  => 'slug',
                'length' => 255,
                'trim'   => true
            ],
            // вид ярлыка (слага)
            'slug_type' => 'slug_type',
            // материал участвует в поиске
            'in_search' => [
                'field' => 'in_search', 
                'type'  => 'int'
            ],  
            // материал выводится на карте сайта
            'in_sitemap' => [
                'field' => 'in_sitemap', 
                'type'  => 'int'
            ],
            // карта сайта (Sitemap) включает материал
            'sitemap_enabled' => [
                'field' => 'sitemap_enabled', 
                'type'  => 'int'
            ],
            // карта сайта (Sitemap), приоритетность (от 0.0 до 1.0)
            'sitemap_priority' => [
                'field' => 'sitemap_priority', 
                'type'  => 'float'
            ],
            // карта сайта (Sitemap), частота изменения
            'sitemap_frequency' => [
                'field'  => 'sitemap_frequency', 
                'length' => 10,
                'trim'   => true
            ],
            // материал выводится в фид (лента новостей, анонсов статей в формате RSS, RDF, Atom):
            'feed_enabled'=> [
                'field' => 'feed_enabled', 
                'type'  => 'int'
            ],
            // метатег материала title
            'title' => [
                'field' => 'title',
                'length' => 255,
                'trim'   => true
            ],
            // метатег материала keywords
            'keywords' => [
                'field' => 'meta_keywords',
                'trim'  => true
            ],
            // метатег материала description
            'description' => [
                'field' => 'meta_description',
                'trim'  => true
            ],
            // метатег robots
            'robots' => [
                'field'  => 'meta_robots',
                'length' => 50,
                'trim'   => true
            ],
            // метатег googlebot
            'googlebot' => [
                'field'  => 'meta_googlebot',
                'length' => 50,
                'trim'   => true
            ],
            // метатег yandex
            'yandex' => [
                'field'  => 'meta_yandex',
                'length' => 50,
                'trim'   => true
            ],
            // все остальные метатеги (JSON формат)
            'meta_tags' => [
                'field' => 'meta_tags',
                'trim'  => true
            ],
            // кэшировать
            'caching' => [
                'field' => 'caching', 
                'type'  => 'int'
            ],
            // поля (JSON формат)
            'fields' => [
                'field' => 'fields',
                'trim'  => true
            ],
            // метки через разделитель ","
            'tags' => [
                'field' => 'tags',
                'trim'  => true
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function afterImportAttributes(array $columns): array
    {
        // текст материала без форматирования
        if (!empty($columns['text'])) {
            $columns['text_plain'] = strip_tags($columns['text']);
        }
        // анонс материала без форматирования
        if (!empty($columns['announce'])) {
            $columns['announce_plain'] = strip_tags($columns['announce']);
        }
        // дата и время публикации материала
        if (empty($columns['publish_date']))
            $columns['publish_date'] = gmdate('Y-m-d H:i:s'); // UTC
        else
            $columns['publish_date'] = gmdate('Y-m-d', strtotime($columns['publish_date']));
        // хэш ярлыка (слага)
        if (!empty($columns['slug'])) {
            $columns['slug_hash'] = md5($columns['slug']);
        }
        return $columns;
    }
}
