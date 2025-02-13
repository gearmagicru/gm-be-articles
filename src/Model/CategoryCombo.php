<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Model;

use Gm\Panel\Data\Model\Combo\ComboModel;

/**
 * Модель данных выпадающего списка пунктов главного меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Model
 * @since 1.0
 */
class CategoryCombo extends ComboModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{article_category}}',
            'primaryKey' => 'id',
            'searchBy'   => 'name',
            'order'      => ['name' => 'ASC'],
            'fields'     => [
                ['id'],
                ['type'],
                ['name']
            ]
        ];
    }
}
