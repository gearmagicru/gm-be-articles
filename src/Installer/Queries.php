<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{article}}'],
    'create' => [
        '{{article}}' => function () {
            return "CREATE TABLE `{{article}}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `type_id` int(11) unsigned DEFAULT NULL,
                `category_id` int(11) unsigned DEFAULT NULL,
                `language_id` int(11) unsigned DEFAULT NULL,
                `index` int(11) unsigned DEFAULT '1',
                `hits` int(11) unsigned DEFAULT '0',
                `fixed` tinyint(1) unsigned DEFAULT '0',
                `template` varchar(255) DEFAULT NULL,
                `page_template` varchar(255) DEFAULT NULL,
                `has_shortcode` tinyint(1) unsigned DEFAULT '0',
                `publish` tinyint(1) unsigned DEFAULT '1',
                `publish_on_main` tinyint(1) unsigned DEFAULT '1',
                `publish_in_categories` varchar(255) DEFAULT NULL,
                `publish_date` datetime DEFAULT NULL,
                `title` varchar(255) DEFAULT NULL,
                `header` varchar(255) DEFAULT NULL,
                `image` varchar(255) DEFAULT NULL,
                `text` text,
                `text_plain` text,
                `announce` text,
                `announce_plain` text,
                `slug` varchar(255) DEFAULT NULL,
                `slug_hash` varchar(32) DEFAULT NULL,
                `slug_type` smallint(6) unsigned DEFAULT '1',
                `in_search` tinyint(1) unsigned DEFAULT '1',
                `in_sitemap` int(1) unsigned DEFAULT '1',
                `sitemap_enabled` tinyint(1) unsigned DEFAULT '1',
                `sitemap_priority` float(2,1) unsigned DEFAULT '0.0',
                `sitemap_frequency` varchar(10) DEFAULT NULL,
                `feed_enabled` tinyint(1) unsigned DEFAULT '0',
                `meta_keywords` text,
                `meta_description` text,
                `meta_robots` varchar(50) DEFAULT NULL,
                `meta_googlebot` varchar(50) DEFAULT NULL,
                `meta_yandex` varchar(50) DEFAULT NULL,
                `meta_tags` text,
                `media_paths` text,
                `caching` tinyint(1) unsigned DEFAULT '0',
                `fields` text,
                `tags` text,
                `_updated_date` datetime DEFAULT NULL,
                `_updated_user` int(11) unsigned DEFAULT NULL,
                `_created_date` datetime DEFAULT NULL,
                `_created_user` int(11) unsigned DEFAULT NULL,
                `_lock` tinyint(1) unsigned DEFAULT '0',
                PRIMARY KEY (`id`),
                KEY `publish` (`publish`),
                KEY `publish-slug_type` (`publish`,`slug_type`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],
    'insert' => [
        '{{term}}' => [
            [
                'name'           => 'article',
                'component_id'   => 'gm.be.articles',
                'component_type' => 'module'
            ]
        ]
    ],
    'delete' => [
        '{{term}}' => [
            [
                'name'         => 'article',
                'component_id' => 'gm.be.articles'
            ]
        ]
    ],

    'run' => [
        'install'   => ['drop', 'create', 'insert'],
        'uninstall' => ['drop']
    ]
];