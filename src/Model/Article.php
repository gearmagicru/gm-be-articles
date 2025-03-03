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
use Gm\Backend\References\MediaDialogs\Model\MediaDialog;

/**
 * Активная запись материала.
 * 
 * Материал имеет поле
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Model
 * @since 1.0
 */
class Article extends \Gm\Site\Data\Model\Article
{
    /**
     * @see Article::getMediaDialog()
     * 
     * @var MediaDialog
     */
    protected MediaDialog $_mediaDialog;

    /**
     * Возвращает активную запись медиа диалога.
     * 
     * @return MediaDialog
     */
    public function getMediaDialog(): MediaDialog
    {
        if (!isset($this->_mediaDialog)) {
            $this->_mediaDialog = Gm::$app->extensions->getModel('MediaDialog', 'gm.be.references.media_dialogs');
        }
        return $this->_mediaDialog;
    }

    /**
     * Возвращает коротий путь к вызываемой папке указанного диалога.
     * 
     * Вызываемая папка диалога - это медиапапка материала, которая содержит:
     * изображения, видео и т.п. и добавляется в дерево папок указанного диалога
     * с возможность выбора и просмотра ёё файлов.
     * 
     * Короткий путь может иметь вид '2024/01/01/12345', он будет добавлен к основному 
     * пути медиапапки.
     * 
     * @param string|int $dialog Псевдоним диалога или его идентификатор.
     * 
     * @return string
     */
    public function getMediaPathByDialog(string|int $dialog): ?string
    {
        static $mediaPaths = [];

        if (isset($mediaPaths[$dialog])) return $mediaPaths[$dialog];

        /** @var string|null $mediaPath */
        $mediaPath = $this->getMediaPath($dialog);
        if ($mediaPath === null) {
            $mediaPath = $this->defineMediaPath($dialog, true);
        }
        return $mediaPaths[$dialog] = $mediaPath;
    }

    /**
     * Определяет (коротий) путь к вызываемой папке указанного диалога.
     * 
     * Примеры возращаемого пути к вызываемой папке диалога:
     * - '2024/01/01/12345';
     * - 'public/uploads/i/2024/01/01/12345'.
     * 
     * @param string|int $dialog Псевдоним диалога или его идентификатор.
     * @param bool $short Если значение `true`, то возвратит короткий путь, например 
     *     '2024/01/01/12345' (по умолчанию `true`).
     * 
     * @return string
     */
    public function defineMediaPath(string|int $dialog, bool $short = true): string
    {
        if (is_numeric($dialog))
            $mediaDialog = $this->getMediaDialog()->get($dialog);
        else
            $mediaDialog = $this->getMediaDialog()->getByAlias($dialog);
        if ($mediaDialog) {
            return $mediaDialog->getMediaPath(['id' => $this->id ?: $this->getNextId()], $short);
        }
        return '';
    }

    /**
     * Удаляет все записи.
     * 
     * @throws \Gm\Db\Adapter\Driver\Exception\CommandException Невозможно выполнить инструкцию SQL.
     */
    public function deleteAll()
    {
        $this->getDb()
            ->createCommand()
                ->truncateTable($this->tableName())
                ->execute();
    }
}
