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
use Gm\Db\Sql;
use Gm\Helper\Str;
use Gm\Helper\Url;
use Gm\Site\Data\Model\Article;
use Gm\Panel\Data\Model\GridModel;

/**
 * Модель данных списка материала.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Articles\Model
 * @since 1.0
 */
class Grid extends GridModel
{
    /**
     * Тип материала.
     * 
     * @var array|null
     */
    public ?array $articleType = null;

    /**
     * Вид ярлыка (слага).
     * 
     * @var array
     */
    protected array $slugTypes = [];

    /**
     * Языки.
     * 
     * @var array
     */
    protected array $languages = [];

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => true,
            'tableName'  => '{{article}}',
            'tableAlias' => 'article',
            'primaryKey' => 'id',
            'fields'     => [
                ['type_id', 'alias' => 'type'], // тип материала
                ['index', 'direct' => 'article.index'], // порядковый номер
                ['header'], // заголовок
                ['template'], // шаблон материала
                ['page_template',  'alias' => 'pageTemplate'], // шаблон страницы
                ['announce', 'alias' => 'announcePlain'], // краткое описание
                ['slug', 'direct' => 'article.slug'], // ярлык
                ['slug_type', 'alias' => 'slugType'], // вид ярлыка (слага)
                ['url'], // полный URL-адрес
                ['category_name', 'alias' => 'categoryName', 'direct' => 'category.name'], // категория материала
                ['category_slug_path', 'alias' => 'categorySlugPath'], // URL-путь категории
                ['publish_date', 'alias' => 'publishDate'], // дата публикации
                ['language_slug', 'alias' => 'languageSlug'], // слаг языка
                ['language_name', 'alias' => 'languageName', 'direct' => 'article.language_id'], // язык
                ['caching', 'direct' => 'article.caching'], // кэширование
                ['hits'], // посещений
                // Директивы индексирования и показа контента:
                ['indexing', 'direct' => 'article.meta_robots'], // индексирование
                ['meta_robots', 'alias' => 'metaRobots'], // метатег robots
                ['meta_googlebot', 'alias' => 'metaGoogle'], // метатег googlebot
                ['meta_yandex', 'alias' => 'metaYandex'], // метатег yandex
                // Публикации статьи:
                ['publish_on_main', 'alias' => 'publishOnMain'], // на главной странице сайта
                ['publish_in_categories', 'alias' => 'publishInCategories'], // в разделах сайта
                ['publish', 'alias' => 'publish', 'direct' => 'article.publish'], // на сайте
            ],
            'order' => ['publish_date' => 'DESC'],
            'resetIncrements' => ['{{article}}']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        // вид ярлыка (слага)
        $this->slugTypes = [
            Article::SLUG_STATIC  => $this->t('Static'),
            Article::SLUG_DYNAMIC => $this->t('Dynamic'),
            Article::SLUG_HOME    => $this->t('Main')
        ];

        // все доступные языки
        $this->languages = Gm::$app->language->available->getAllBy('code');

        $this
            ->on(self::EVENT_BEFORE_DELETE, function ($someRows, &$canDelete) {
                // удалить вложения
                if (!$this->deleteAttachments($someRows)) {
                    $canDelete = false;
                    // всплывающие сообщение
                    $this->response()
                        ->meta
                            ->cmdPopupMsg(
                                $this->t('Attachments (files) of materials were partially removed'), 
                                Gm::t(BACKEND, 'Deletion'), 
                                'error'
                            );
                    return;
                }
                // удалить метки
                $this->deleteTags($someRows);
            })
            ->on(self::EVENT_AFTER_DELETE, function ($someRows, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid('grid-' . $this->articleType['id']);
            });
    }

    /**
     * Удаляет вложения (файлы) указанных материалов.
     * 
     * @param bool $someRows Значение `true`, если выбранные материалы, иначе все.
     * 
     * @return bool
     */
    protected function deleteAttachments(bool $someRows = true): bool
    {
        $where = [];
        // только выделенные записи
        if ($someRows)
            $where = ['id' => $this->getIdentifier()];
        // все записи
        else {
            // если отображаются материалы только указанного вида
            if ((int) $this->articleType['all'] == 0) {
                $where = ['type_id' => $this->articleType['id']];
            }
        }

        $article = new Article();
        /** @var int|false $result Результат удаления вложения */
        $result = $article->deleteAttachments($where, true);
        return $result !== false;
    }

    /**
     * Удаляет все метки материала.
     * 
     * @param bool $someRecords Значение `true`, если выбранные материалы, иначе все.
     * 
     * @return bool
     */
    protected function deleteTags(bool $someRows = true): bool
    {
        /** @var int|null $termId Идент. термина */
        $termId = Gm::getTermId('article');
        if ($termId === null) return false;

        // только выделенные записи
        if ($someRows) {
            $rowId = $this->getIdentifier();
            $groupId = null;
        // все записи
        } else {
            $rowId = null;
            // если отображаются материалы только указанного вида
            if ((int) $this->articleType['all'] == 0)
                $groupId = $this->articleType['id'];
            else
                $groupId = null;
        }
        return Gm::$app->tagger->deleteTermTagsBy($rowId, null, $termId, $groupId) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRow(array $row): array
    {
        // Язык
        $languageSlug = null;
        if ($row['language_id']) {
            $language = $this->languages[(int) $row['language_id']] ?? null;
            if ($language) {
                $languageSlug = $language['slug'];
                $row['language_name'] = $language['shortName'] . ' (' . $languageSlug . ')';
            }
        }

        // Вид ярлыка (слага)
        $slugType = $row['slug_type'] ?? 0;
        $row['slug_type'] = $this->slugTypes[$slugType] ?? '';

        // Ярлык (слаг)
        $slug = $row['slug'];
        if ($slugType == Article::SLUG_DYNAMIC && $slug) {
            $slug = Str::idToStr($slug, $row['id']);
        } 
        $row['slug'] = $slug;

        // URL-адрес
        $row['url'] = Url::to([$row['category_slug_path'], 'basename' => $slug, 'langSlug' => $languageSlug]);

        // Дата публикации
        $row['publish_date'] = $this->fetchDateTimeField($row['publish_date']);
        // Индексирование
        $indexing = '';
        if ($row['meta_robots']) {
            $indexing = '<div>' . $row['meta_robots'] . '</div>';
        }
        if ($row['meta_yandex']) {
            $indexing .= '<div><span class="gm-articles__grid-cell-yandex">Y: </span>' . $row['meta_yandex'] . '</div>';
        }
        if ($row['meta_googlebot']) {
            $indexing .= '<div><span class="gm-articles__grid-cell-google">G: </span>' . $row['meta_googlebot'] . '</div>';
        }
        $row['indexing'] = $indexing;
        return $row;
    }

    /**
     * {@inheritdoc}
     * 
     * Удалить записи только указанного материала в запросе.
     */
    protected function deleteProcessCondition(array &$where): void
    {
        // если отображаются материалы только указанного вида
        if ((int) $this->articleType['all'] == 0) {
            $where['type_id'] = $this->articleType['id'];
        }

        parent::deleteProcessCondition($where);
    }

    /**
     * {@inheritdoc}
     * 
     * Удалить записи только указанного материала в запросе.
     */
    protected function deleteAllProcessCondition(array &$where): void
    {
        // если отображаются материалы только указанного вида
        if ((int) $this->articleType['all'] == 0) {
            $where['type_id'] = $this->articleType['id'];
        }

        parent::deleteAllProcessCondition($where);
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // заголовок контекстного меню записи
        $row['popupMenuTitle'] = $row['header'];
    }

    /**
     * {@inheritdoc}
     * 
     * Возвращает записи материала согласно указанному типу. Если тип не указан, возвратит
     * все записи.
     * 
     * Результирующий запрос:
     * SELECT SQL_CALC_FOUND_ROWS `article`.*, `category`.`name` AS `category_name`, `category`.`slug_path` AS `category_slug_path` 
     * FROM `gm_article` `article` LEFT JOIN `{{article_category}}` `category` ON `category`.`id` = `article`.`category_id` 
     * WHERE `article`.`type_id` = ... ORDER BY `publish_date` DESC LIMIT 50 OFFSET 0
     */
    public function selectAll(string $tableName = null): array
    {
        /** @var \Gm\Db\Sql\Select $select */
        $select = new Sql\Select();
        $select
            ->from(['article' => $this->dataManager->tableName])
            ->columns(['*'], true)
            ->quantifier(new Sql\Expression('SQL_CALC_FOUND_ROWS'))
            ->join(
                ['category' => '{{article_category}}'],
                'category.id = article.category_id',
                ['category_name' => 'name', 'category_slug_path' => 'slug_path'],
                Sql\Select::JOIN_LEFT
            );

        // если указан тип материала и не указано выводить все материалы
        if ($this->articleType && $this->articleType['all'] == 0) {
            $select->where(['article.type_id' => $this->articleType['id']]);
        }

        /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
        $command = $this->buildQuery($select);
        $this->beforeFetchRows();
        $rows = $this->fetchRows($command);
        $rows = $this->afterFetchRows($rows);
        return $this->afterSelect($rows, $command);
    }
}
