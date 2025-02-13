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
use Gm\Api\Api as BaseApi;

/**
 * API статей сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Model
 * @since 1.0
 */
class Api extends BaseApi
{
    /**
     * {@inheritdoc}
     */
    public array $routes = [
        //  атрибуты стать по указанному идентификатору
        'api/article/view' => 'articleView'
    ];

    /**
     * Возвращает атрибуты стать по указанному идентификатору.
     * 
     * @return null|array
     */
    public function articleView(): ?array
    {
        // идентификатор статьи
        $id = Gm::$app->request->getPost('id', null, 'int');
        if (empty($id)) {
            $this->addError(
                GM_MODE_DEV ? sprintf('Incorrectly passed parameter "ID", module "%s", API class "%s"', $this->module['id'], $this->getClass()) : '',
                422
            );
            return null;
        }
        /** @var Article $article статья */
        $article = new Article();
        $article = $article->selectByPk($id);
        return $article ? $article->getAttributes() : null;
    }
}
