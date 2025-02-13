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

/**
 * API статей сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Api
 * @since 1.0
 */
class Api extends \Gm\Api\Api
{
    /**
     * {@inheritdoc}
     */
    public function getRoutes(): array
    {
        return [
            //  атрибуты стать
            'api/article/view' => 'articleView'
        ];
    }

    /**
     * Атрибуты статьи.
     * 
     * Маршрут запроса: 'api/article/view' (https://domains.com/api/article/view).
     * Параметры запроса (метод POST):
     * - id, идентификатор статьи.
     * 
     * @return false|array|null Возвращает значение `false`, если параметры запроса 
     *     не прошли проверку. Значение `null`, если статья отсутствует.
     */
    public function articleView(): false|array|null
    {
        // идентификатор статьи
        $id = Gm::$app->request->getPost('id', null, 'int');
        if (empty($id)) {
            $this->addError(
                GM_MODE_DEV ?
                sprintf('Incorrectly passed parameter "ID", module "%s", API class "%s"', $this->module['id'], $this->getClass()) : 'Incorrectly passed parameter "ID"',
                422
            );
            return false;
        }

        /** @var Article $article статья */
        $article = new Article();
        $article = $article->selectByPk($id);
        return $article ? $article->getAttributes() : null;
    }
}
