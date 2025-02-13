<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Model;

use Gm;
use Gm\Helper\Str;
use Gm\Data\Model\DataModel;
use Gm\Mvc\Module\BaseModule;

/**
 * Модель данных элементов панели справочников.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Model
 * @since 1.0
 */
class Desk extends DataModel
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Articles\Module
     */
    public BaseModule $module;

    /**
     * Длина заголовка.
     * 
     * @var int
     */
    public int $titleLength = 43;

    /**
     * Возвращает элементы панели.
     * 
     * @return array
     */
    public function getItems(): array
    {
        /** @var null|\Gm\Backend\References\Articles\Model\ArticleType */
        $articles = Gm::$app->extensions->getModel('ArticleType', 'gm.be.references.articles');
        if ($articles) {
            $articles = $articles->fetchAll();
        }

        $items = [];
        if ($articles) {
            $noneIcon = $this->module->getAssetsUrl() . '/images/icon.svg';
            foreach ($articles as $article) {
                if (empty($article['enabled'])) continue;
                $name = $article['name'] ?: '';
                $desc = $article['description'] ?: '';
                $items[] = [
                    'title'       => $name,
                    'description' => Str::ellipsis($desc, 0, $this->titleLength),
                    'tooltip'     => $desc,
                    'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                    'icon'        => empty($article['icon']) ? $noneIcon : $article['icon']
                ];
            }
/*
            $items[] = [
                'title'       => 'Сериалы',
                'description' => 'Страницы с описанием сериалов',
                'tooltip'     => $desc,
                'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                'icon'        => '/modules/gm/gm.be.references.articles/assets/images/types/1.svg'
            ];
            $items[] = [
                'title'       => 'Актёры',
                'description' => 'Страницы с описанием актёров сериалов',
                'tooltip'     => $desc,
                'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                'icon'        => '/modules/gm/gm.be.references.articles/assets/images/types/6.svg'
            ];
            $items[] = [
                'title'       => 'Эпизоды',
                'description' => 'Страницы с обзором сериий',
                'tooltip'     => $desc,
                'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                'icon'        => '/modules/gm/gm.be.references.articles/assets/images/types/2.svg'
            ];
            $items[] = [
                'title'       => 'Фото в эпизодах',
                'description' => 'Страницы с фото из эпизодов сериалов',
                'tooltip'     => $desc,
                'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                'icon'        => '/modules/gm/gm.be.references.articles/assets/images/types/7.svg'
            ];
            $items[] = [
                'title'       => 'Тизеры',
                'description' => 'Страницы с тизерами сериала',
                'tooltip'     => $desc,
                'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                'icon'        => '/modules/gm/gm.be.references.articles/assets/images/types/3.svg'
            ];
            $items[] = [
                'title'       => 'Трейлеры',
                'description' => 'Страницы с трейлерами сериала',
                'tooltip'     => $desc,
                'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                'icon'        => '/modules/gm/gm.be.references.articles/assets/images/types/5.svg'
            ];
            $items[] = [
                'title'       => 'Новости',
                'description' => 'Страницы с новостями сайта',
                'tooltip'     => $desc,
                'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                'icon'        => empty($article['icon']) ? $noneIcon : $article['icon']
            ];
            $items[] = [
                'title'       => 'Блог',
                'description' => 'Страницы статей блога',
                'tooltip'     => $desc,
                'widgetUrl'   => '@backend/articles/grid?type=' . $article['id'],
                'icon'        => '/modules/gm/gm.be.references.articles/assets/images/types/8.svg'
            ];*/
        }
        return $items;
    }
}
