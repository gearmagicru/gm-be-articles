<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Материалы сайта',
    '{description}' => 'Виды статей сайта с соответствующим представлением информации',
    '{permissions}' => [
        'any'    => ['Полный доступ', 'Просмотр и внесение изменений в материал сайта'],
        'view'   => ['Просмотр', 'Просмотр материала сайта'],
        'read'   => ['Чтение', 'Чтение материала сайта'],
        'add'    => ['Добавление', 'Добавление материала сайта'],
        'edit'   => ['Изменение', 'Изменение материала сайта'],
        'delete' => ['Удаление', 'Удаление материала сайта'],
        'clear'  => ['Очистка', 'Удаление всего материала сайта']
    ],

    // Form
    '{form.title}' => 'Добавление "{0}"',
    '{form.titleTpl}' => 'Изменение "{header}"',
    // Form: поля / aтрибуты материала
    'Attributes' => 'Атрибуты',
    'Language' => 'Язык',
    'The language in which the article (article) will be displayed' => 'Язык на котором будет отображаться материал',
    'Article type' => 'Тип материала',
    'The type of the slug determines the rules for the formation of the URL of the article' => 'Вид ярлыка определяет правила формирования URL-адреса материала',
    'Static' => 'Статический',
    'Dynamic' => 'Динамический',
    'Main' => 'Основной',
    'Template' => 'Шаблон',
    'Article display template' => 'Шаблон отображения материала',
    'Page template' => 'Шаблон страницы',
    'Category' => 'Категория',
    'Category of article, the section in which the article will be located' => 'Категория материала, раздел в котором будет находиться материал',
    'SEF URL article' => 'ЧПУ URL материала',
    'Slug' => 'Ярлык',
    'The Slug is a version of a name, a unique part of a URL. These are all lowercase letters and only Latin letters, numbers and hyphens.' 
        => 'Ярлык (слаг) - это версия имени, уникальная часть URL-адреса. Это все строчные буквы и только буквы на латинице, цифры и дефисы. Если не указан, он будет создан автоматически из названия.',
    'Article header' => 'Заголовок материала',
    'Image' => 'Изображение',
    'Preview image, displayed along with the title and lead of the article' => 'Изображение для превью, отображается вместе с заголовком и анонсом материала',
    'Date of publication of the article' => 'Дата публикации материала',
    'Date' => 'Дата',
    'date' => 'дата',
    'Time' => 'Время',
    'Publish article:' => 'Опубликовать материал:',
    'on the main page of the site' => 'на главной странице сайта',
    'in site sections' => 'в разделах сайта',
    'on the site' => 'на сайте',
    // Form: поля / вкладка / SEO / Метатег материала
    'Name' => 'Название',
    'Article meta tag (title, description, keywords)' => 'Метатег материала (title, description, keywords)',
    'title of the title in the browser tab' => 'название заголовка во вкладке браузера',
    'Keywords' => 'Ключевые слова',
    'search engines use it to determine the relevance of a link' 
        => 'поисковые системы используют для того, чтобы определить релевантность ссылки. Необходимо использовать только те слова, которые содержатся на самой странице, количество слов — не более десяти',
    'Description' => 'Описание',
    'used by search engines for indexing, as well as when creating an annotation in the search results on demand' 
        => 'используется поисковыми системами для индексации, а также при создании аннотации в выдаче по запросу. При отсутствии тега поисковые системы выдают в аннотации первую строку документа или отрывок, содержащий ключевые слова',
    // Form: поля / вкладка / SEO / Карта сайта
    'Sitemap for search engines' => 'Карта сайта (Sitemap) для поисковых систем',
    'includes article' => 'включает материал',
    'priority' => 'приоритетность',
    'The priority of URLs relative to other URLs on your site' => 'Приоритетность URL относительно других URL на Вашем сайте.',
    'rate of change' => 'частота изменения',
    'The likely frequency of changes to this page' 
        => 'Вероятная частота изменения этой страницы. Это значение предоставляет общую информацию для поисковых систем и может не соответствовать точно частоте сканирования этой страницы.',
        'always' => 'всегда',
        'hourly' => 'ежечасно',
        'daily' => 'ежедневно',
        'weekly' => 'еженедельно',
        'monthly' => 'ежемесячно',
        'yearly' => 'ежегодно',
        'never' => 'никогда',
    // Form: поля / вкладка / SEO / Фид
    'Feed (news feed, article announcements in RSS, RDF, Atom format)'  => 'Фид (лента новостей, анонсов статей в формате RSS, RDF, Atom)',
    // Form: поля / вкладка / SEO / Директивы индексирования
    'Indexing and serving content directives' => 'Директивы индексирования и показа контента',
    'for all robots' => 'для всех роботов',
    'metatag "robots"' => 'метатег &laquo;robots&raquo;',
    'robot only "Google"' => 'только для робота <span class="gm-articles__google"><i>G</i><i>o</i><i>o</i><i>g</i><i>l</i><i>e</i></span>',
    'metatag "googlebot"' => 'метатег &laquo;googlebot&raquo;',
    'robot only "Yandex"' => 'только для робота <span class="gm-articles__yandex"><i>Я</i>ндекс</span>',
    'metatag "yandex"' => 'метатег &laquo;yandex&raquo;',
    'all - no restrictions on indexing and displaying content, equivalent to index, follow;' 
        => '<strong>all</strong> - нет ограничений на индексирование и показ контента, эквивалент index, follow;',
    'none - is equivalent to noindex, nofollow;' 
        => '<strong>none</strong> - является эквивалентом noindex, nofollow;',
    'index - allows robots to index the page;' 
        => '<strong>index</strong> - разрешает роботам индексировать страницу. Страница будет участвовать в результатах поиска;',
    'noindex - prevents robots from indexing the page;' 
        => '<strong>noindex</strong> - запрещает роботам индексировать страницу. Страница не будет участвовать в результатах поиска;',
    'follow - allows robots to follow links on the page;' 
        => '<strong>follow</strong> - разрешает роботам переходить по ссылкам на странице',
    'nofollow - prevents robots from following links on the page.' 
        => '<strong>nofollow</strong> - запрещает роботам переходить по ссылкам на странице.',
    'more about meta tags: Google, Yandex' 
        => 'подробнее о <a class="gm-articles__link" target="_blank" href="https://developers.google.com/search/docs/advanced/crawling/special-tags?hl=ru">метатегах Google<a>, <a class="gm-articles__link" target="_blank" href="https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html">метатегах Яндекс<a>',
    // Form: поля / вкладка / SEO / Особые директивы метатегов
    'Special meta tag directives' => 'Особые директивы метатегов',
    'directive "noarchive" of meta tag "robots"' => '<strong>noarchive</strong> метатега <span>&laquo;robots&raquo;</span>',
    'do not show the link to the saved copy in the search results' 
        => 'не показывать ссылку на сохраненную копию в результатах поиска (запрещает показывать ссылку на кеш). <span>Яндекс, Google, Yahoo.</span>',
    'directive "noyaca" of meta tag "yandex"' => '<strong>noyaca</strong> метатега <span>&laquo;yandex&raquo;</span>',
    'ban on using descriptions from Yandex' 
        => 'запрет на использование описания из Яндекс.Каталога при формировании сниппета поисковой выдачи. <span>Яндекс.</span>',
    'directive "noydir" of meta tag "robots"' 
        => '<strong>noydir</strong> метатега <span>&laquo;robots&raquo;</span>',
    'restricting the borrowing of descriptions and site names from the Yahoo directory' 
        => 'ограничение заимствования описаний и названия сайта из каталога Yahoo при формировании сниппета поисковой выдачи. <span>Yahoo.</span>',
    'directive "noodp" of meta tag "robots"' 
        => '<strong>noodp</strong> метатега <span>&laquo;robots&raquo;</span>',
    'limiting the borrowing of site descriptions and names from the Open Directory Project' 
        => 'ограничение заимствования описаний и названия сайта из Open Directory Project (каталог DMOZ). <span>Яндекс, Google, Yahoo, Bing.</span>',
    'directive "notranslate" of meta tag "google"' 
        => '<strong>notranslate</strong> метатега <span>&laquo;google&raquo;</span>',
    'informs Google that this page should not be translated into other languages' 
        => 'сообщает Google, что эту страницу не следует переводить на другие языки. <span>Google.</span>',
    'directive "nopagereadaloud" of meta tag "google"' 
        => '<strong>nopagereadaloud</strong> метатега <span>&laquo;google&raquo;</span>',
    'prevents browsers from performing such Google Assistant voice commands' 
        => 'запрещает браузерам выполнять такие голосовые команды Google Ассистента, как "Прочитать" или "Прочитать эту страницу", позволяющие озвучить опубликованный на странице текст. <span>Google.</span>',
    'directive "noimageindex" of meta tag "robots"' 
        => '<strong>noimageindex</strong> метатега <span>&laquo;robots&raquo;</span>',
    'prohibits indexing images on the page' 
        => 'запрещает индексировать изображения на странице. <span>Google.</span>',
    'directive "nosnippet" of meta tag "robots"' 
        => '<strong>nosnippet</strong> метатега <span>&laquo;robots&raquo;</span>',
    'do not show text or video in search results' 
        => 'не показывать в результатах поиска текстовый фрагмент или видео. Статические уменьшенные изображения (если имеются) могут по-прежнему отображаться, если это повышает удобство для пользователей. Это условие выполняется для всех типов результатов поиска (веб-поиск Google, Google Картинки, рекомендации). <span>Google.</span>',
    'directive "unavailable_after" of meta tag "robots"' 
        => '<strong>unavailable_after</strong> метатега <span>&laquo;robots&raquo;</span>',
    'prevents the page from being shown in search results after the date and time' 
        => 'директива запрещает показывать страницу в результатах поиска после даты и времени, указанных в одном из основных форматов. <span>Google.</span>',
    'directive "max-snippet" of meta tag "robots"' 
        => '<strong>max-snippet</strong> метатега <span>&laquo;robots&raquo;</span>',
    'limitation on the number of characters in a text fragment' 
        => 'ограничение на количество символов в текстовом фрагменте, который показывается в результатах поиска.. <span>Google.</span>',
    'directive defines the maximum size of images' 
        => 'определяет максимальный размер изображений, которые могут показываться в результатах поиска для этой страницы. <span>Google.</span>',
    'directive "max-image-preview" of meta tag "robots"' 
        => '<strong>max-image-preview</strong> метатега <span>&laquo;robots&raquo;</span>',
    'value' => 'значение',
    'no picture' => 'нет изображения',
    'medium image' => 'среднее изображение',
    'large image' => 'крупное изображение',
    'sets a limit on the duration in seconds for a video fragment from a page' 
        => 'директива задает для фрагмента видео со страницы ограничение по длительности в секундах при показе в результатах поиска. <span>Google.</span>',
    'directive "max-video-preview" of meta tag "robots"' 
        => '<strong>max-video-preview</strong> метатега <span>&laquo;robots&raquo;</span>',
    // Form: поля / вкладка / анонс
    'Announce' => 'Анонс',
    // Form: поля / вкладка / текст
    'Text' => 'Текст',
    // Form: поля / вкладка / дополнительно
    'Tags' => 'Метки',
    'Labels or tags that will be displayed on the content page as hyperlinks' 
        => 'Метки или теги, которые будут отображаться на странице материала в виде гиперссылок',
    'Additionally' => 'Дополнительно',
    'Index' => 'Порядок',
    'Index number' => 'Порядковый номер',
    'The ordinal number of the article is used to sort the articles in the list' => 'Порядковый номер материала применяется для сортировки материала в списке',
    'Number of hits' => 'Количество посещений',
    '<span>search</span>' => '<span>поиск</span>',
    'the article participates in the search on the site' => 'материал участвует в поиске на сайте',
    '<span>sitemap</span>' => '<span>карта сайта</span>',
    'the sitemap includes a link to the article' => 'карта сайта включает ссылку на материал',
    '<span>caching</span>' => '<span>кэширование</span>',
    // Form: всплывающие сообщения
    'Unable to delete attachments (files) of the material' => 'Невозможно удалить вложения (файлы) материала.',

    // Grid: контекстное меню записи
    'Edit article' => 'Редактировать',
    'Copy article' => 'Копировать',
    // Grid: инструменты
    'Adding a new record "{0}"' => 'Добавление новой записи "{0}"',
    'Deleting selected records "{0}"' => 'Удаление выделенных записей "{0}"',
    'Deleting all records "{0}"' => 'Удаление всех записей "{0}"',
    'Do you really want to delete all records "{0}"?' => 'Вы действительно хотите удалить все записи "{0}"?',
    // Grid: столбцы
    'Header' => 'Заголовок',
    'Category' => 'Категория',
    'Type' => 'Тип',
    'Slug type' => 'Вид ярлыка',
    'Date of publication' => 'Дата публикации',
    'Date of announcement' => 'Дата анонса',
    'Caching' => 'Кэширование материала',
    'Indexing' => 'Индексирование',
    'URL path' => 'URL-путь',
    'Publishing an article' => 'Публикации материала',
    'View article' => 'Посмотреть материал',
    'the article is not indexed by robots' => 'материал не индексируется роботами',
    'yes' => 'да',
    'no' => 'нет',
    // Grid: всплывающие сообщения / заголовок
    'Show' => 'Показать',
    'Hide' => 'Скрыть',
    // Grid: всплывающие сообщения / текст
    'The record has been hidden' => 'Запись снята с публикации.',
    'The record has been shown' => 'Запись успешно опубликована.',
    'Attachments (files) of materials were partially removed' => 'Вложения (файлы) материалов были частично удалены.',
];
