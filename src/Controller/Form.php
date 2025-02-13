<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Articles\Controller;

use Gm;
use Gm\Stdlib\BaseObject;
use Gm\Mvc\Module\BaseModule;
use Gm\Widget\TinyMCE\Widget;
use Gm\Panel\Controller\FormController;
use Gm\Backend\Articles\Widget\ArticleWindow;

/**
 * Контроллер формы материала сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Controller
 * @since 1.0
 */
class Form extends FormController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Articles\Module
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public bool $useAppEvents = true;

    /**
     * Тип материала.
     * 
     * @var array|array
     */
    protected ?array $articleType = null;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action, &$result) {
                switch ($action) {
                    case 'view':
                    case 'data':
                    case 'delete':
                        $typeId = Gm::$app->request->getQuery('type', 0, 'int');
                        break;

                    case 'update': 
                    case 'add':
                        $typeId = Gm::$app->request->getPost('type', 0, 'int');
                        break;
                }

                if (isset($typeId)) {
                    if (empty($typeId)) {
                        $this->getResponse()
                            ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['type']));
                        $result = false;
                        return;
                    }

                    // типа материала
                    $type = $this->module->getArticleType($typeId, $action === 'view');
                    if (is_string($type) || $type === null) {
                        $this->getResponse()
                            ->meta->error(
                                is_string($type) ? $type : Gm::t('app', 'Parameter passed incorrectly "{0}"', ['type'])
                            );
                        $result = false;
                        return;
                    }
                    $this->articleType = $type;
                }
            });
    }

    /**
     * Возвращает параметры конфигурации редактора.
     * 
     * @return array
     */
    protected function getEditorWidget(): ?Widget
    {
        /** @var null|Widget $editor */
        $editor = Gm::$app->widgets->get('gm.wd.tinymce');
        if ($editor) {
            $editor->initResponse($this->getResponse());
            return $editor;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): ArticleWindow|false
    {
        /** @var int $articleId */
        $articleId = (int) Gm::$app->router->get('id');
        /** @var \Gm\Site\Data\Model\Article $article */
        $article = $this->getModel('Article');
        $article = $articleId ? $article->getById($articleId) : $article;
        if ($article === null) {
            $this->errorResponse(
                Gm::t(BACKEND, 'The item you selected does not exist or has been deleted')
            );
            return false;
        }

        /** @var Widget|null $editor */
        $editor = $this->getEditorWidget();
        if ($editor) {
            // для плагинов редактора добавляем параметры
            $editor->getSettings()->mediaPaths = [
                // для плагина "gmimage"
                'article-image' => $article->getMediaPathByDialog('article-image'),
                // для плагина "gmlink"
                'article-doc' => $article->getMediaPathByDialog('article-doc')
            ];
        }

        /** @var ArticleWindow $window */
        $window = new ArticleWindow(['article' => $article]);
        $window
            ->setComponents($this->articleType['components'])
            ->setTabs($this->articleType['tabs'])
            ->setTypeId($this->articleType['id']);

        // указаывает редактор для материала (статьи)
        $window->setEditor($editor ? $editor->run() : []);
        // указываем редактор для анонса, но убираем лишнии плагины если они указаны
        if ($editor->id === 'gm.wd.tinymce') {
            $editor->removePlugin(['gmimage', 'gmlink', 'gmshortcode'], true);
            $window->setAnnEditor($editor ? $editor->run() : []);
        }

        $window
            ->title = $this->t('{form.title}', [$this->articleType['name']]);
        return $window;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(string $name = null, array $config = []): ?BaseObject
    {
        $config['articleType'] = $this->articleType;

        return parent::getModel($name, $config);
    }
}
