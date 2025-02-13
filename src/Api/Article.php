<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Api;

use Gm;
use Gm\Db\ActiveRecord;

/**
 * Модель данных статьи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Api
 * @since 1.0
 */
class Article extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function tableName(): string
    {
        return '{{article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'language'    => 'language_id', // язык
            'template'    => 'template', // шаблон
            'category_id' => 'category', // категория
            'slug'        => 'slug', // URL-путь
            'slugHash'    => 'slug_hash',
            'slugType'    => 'slug_type',  // тип URL-адреса
            // Заголовок статьи:
            'header'      => 'header', // название
            'headerImage' => 'header_image', // изображение
            // Дата публикации статьи:
            'publishDate' => 'publish_date',  // дата
            // Дата анонса статьи или события:
            'announceDate' => 'announce_date', // дата
            // Текст:
            'text' => 'text',
            // Краткое описание:
            'textShort' => 'text_short',
            // Опубликовать статью:
            'publishOnMain' => 'publish_on_main', // на главной странице сайта
            'publishInCategories' => 'publish_in_categories', // в разделах сайта
            'publish' => 'publish', // на сайте
            // Метатег статьи (title, description, keywords):
            'title' => 'title', // название заголовка
            'keywords' => 'meta_keywords' , // ключевые слова
            'description' => 'meta_description', // описание
            // Директивы индексирования и показа контента:
            'metaRobots' => 'meta_robots', // метатег robots
            'metaGoogle'=> 'meta_googlebot', // метатег googlebot
            'metaYandex' => 'meta_yandex', // метатег yandex
            'metaTags' => 'meta_tags', // все остальные метатеги
            // Карта сайта (Sitemap) для поисковых систем:
            'sitemapEnabled' => 'sitemap_enabled', // включает статью
            'sitemapPriority' => 'sitemap_priority', // приоритетность
            'sitemapFrequency' => 'sitemap_frequency', // частота изменения
            // Фид (лента новостей, анонсов статей в формате RSS, RDF, Atom):
            'feedEnabled' => 'feed_enabled',
            // Дополнительно:
            'index' => 'index',  // порядковый номер
            'hits' => 'hits', // посещений
            'inSearch' => 'in_search', // поиск
            'inSitemap' => 'in_sitemap', // карта сайта
            'caching' => 'caching', // заголовок
        ];
    }

}
