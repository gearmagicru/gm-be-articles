<?php
/**
 * Модуль веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles;

use Gm;
use Gm\Helper\Json;
use Gm\Backend\References\Articles\Model\ArticleType;

/**
 * Модуль статей сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles
 * @since 1.0
 */
class Module extends \Gm\Panel\Module\Module
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'gm.be.articles';

    /**
     * @param int $typeId
     * 
     * @return null|string|array|ArticleType
     */
    public function getArticleType(int $id, bool $reset = false): null|string|array|ArticleType
    {
        $storage = $this->getStorage();
        if (empty($storage->articleTypes)) {
            $storage->articleTypes = [];
        }

        if (isset($storage->articleTypes[$id])) {
            if ($reset)
                unset($storage->articleTypes[$id]);
            else
                return $storage->articleTypes[$id];
        }

        /** @var null|ArticleType $articleTypeAR */
        $articleTypeAR = Gm::$app->extensions->getModel('ArticleType', 'gm.be.references.articles');
        if ($articleTypeAR) {
            /** @var null|ArticleType $articleType */
            $articleType = $articleTypeAR->selectByPk($id);
            if ($articleType) {
                /** @var false|array $components */
                $components = $articleType->componentsToArray();
                if ($components === false) {
                    return Json::error() . ' (can\'t convert components to array)';
                }

                /** @var false|array $tabs */
                $tabs = $articleType->tabsToArray();
                if ($tabs === false) {
                    return Json::error() . ' (can\'t convert tabs to array)';
                }

                /** @var false|array $fields */
                $fields = $articleType->fieldsToArray();
                if ($fields === false) {
                    return Json::error() . ' (can\'t convert fields to array)';
                }

                /** @var false|array $columns */
                $columns = $articleType->columnsToArray();
                if ($columns === false) {
                    return Json::error() . ' (can\'t convert columns to array)';
                }

                $attributes = $articleType->getAttributes();
                $attributes['components'] = $components;
                $attributes['tabs'] = $tabs;
                $attributes['fields'] = $fields;
                $attributes['columns'] = $columns;
                $storage->articleTypes[$id] = $attributes;
                return $attributes;
            }
        }
        return null;
    }
}
