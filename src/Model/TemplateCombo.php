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
use Gm\Data\DataManager;
use Gm\Panel\Data\Model\Combo\ComboModel;

/**
 * Модель данных выпадающего шаблонов статьи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Model
 * @since 1.0
 */
class TemplateCombo extends ComboModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManager(): ?DataManager
    {
        // нет смысла в менеджере данных
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        /** @var \Gm\Theme\Theme $theme текущая тема сайта */
        $theme = Gm::$app->createFrontendTheme();
        $theme->set();
        /** @var \Gm\Theme\Info\ViewsInfo $viewsInfo */
        $viewsInfo = $theme->getViewsInfo();
        /** @var \Gm\Theme\Info\Translator $description */
        $description = $viewsInfo->getTranslator();
        if ($viewsInfo->load()) {
            $encrypter = Gm::$app->encrypter;
            $items = $viewsInfo->getAll();
            $rows  = [];
            foreach ($items as $path => $info) {
                if ($info['type'] == 'article') {
                    $rows[] = [
                        $encrypter->encryptString($path),
                        $description->translate($info['description'])
                    ];
                }
            }
        }
        return [
            'total' => sizeof($rows),
            'rows'  => $rows
        ];
    }
}
