<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Site materials',
    '{description}' => 'Types of site articles with appropriate presentation of information',
    '{permissions}' => [
        'any'    => ['Full access', 'Viewing and making changes to site articles'],
        'view'   => ['View', 'Viewing site articles'],
        'read'   => ['Reading', 'Reading site articles'],
        'add'    => ['Adding', 'Adding site articles'],
        'edit'   => ['Editing', 'Editing site articles'],
        'delete' => ['Deleting', 'Deleting site articles'],
        'clear'  => ['Clear', 'Deleting all site articles']
    ],

    // Form
    '{form.title}' => 'Add "{0}"',
    '{form.titleTpl}' => 'Edit "{header}"',
    // Form: поля / aтрибуты статьи
    'Attributes' => 'Attributes',
    'Language' => 'Language',
    'The language in which the article (article) will be displayed' => 'The language in which the article (article) will be displayed',
    'Article type' => 'Article type',
    'The type of the slug determines the rules for the formation of the URL of the article' => 'The type of the slug determines the rules for the formation of the URL of the article',
    'Static' => 'Static',
    'Dynamic' => 'Dynamic',
    'Main' => 'Main',
    'Template' => 'Template',
    'Article display template' => 'Article display template',
    'Page template' => 'Page template',
    'Category' => 'Category',
    'Category of article, the section in which the article will be located' => 'Category of article, the section in which the article will be located',
    'SEF URL article' => 'SEF URL article',
    'Slug' => 'Slug',
    'The Slug is a version of a name, a unique part of a URL. These are all lowercase letters and only Latin letters, numbers and hyphens.' 
        => 'The Slug is a version of a name, a unique part of a URL. These are all lowercase letters and only Latin letters, numbers and hyphens.',
    'Article header' => 'Article header',
    'Image' => 'Image',
    'Preview image, displayed along with the title and lead of the article' => 'Preview image, displayed along with the title and lead of the article',
    'Date of publication of the article' => 'Date of publication of the article',
    'Date' => 'Date',
    'date' => 'date',
    'Time' => 'Time',
    'Publish article:' => 'Publish article:',
    'on the main page of the site' => 'on the main page of the site',
    'in site sections' => 'in site sections',
    'on the site' => 'on the site',
    // Form: поля / вкладка / SEO / Метатег материала
    'Name' => 'Name',
    'Article meta tag (title, description, keywords)' => 'Article meta tag (title, description, keywords)',
    'title of the title in the browser tab' => 'title of the title in the browser tab',
    'Keywords' => 'Keywords',
    'search engines use it to determine the relevance of a link' 
        => 'search engines use it to determine the relevance of a link. It is necessary to use only those words that are contained on the page itself, the number of words is no more than ten',
    'Description' => 'Description',
    'used by search engines for indexing, as well as when creating an annotation in the search results on demand' 
        => 'used by search engines for indexing, as well as when creating an annotation in the search results. In the absence of a tag, search engines give the first line of the document or an excerpt containing keywords in the annotation',
    // Form: поля / вкладка / SEO / Карта сайта
    'Sitemap for search engines' => 'Sitemap for search engines',
    'includes article' => 'includes article',
    'priority' => 'priority',
    'The priority of URLs relative to other URLs on your site' => 'The priority of URLs relative to other URLs on your site.',
    'rate of change' => 'rate of change',
    'The likely frequency of changes to this page' 
        => 'The likely frequency of changes to this page. This value provides general information to search engines and may not correspond exactly to the crawl rate of this page.',
        'always' => 'always',
        'hourly' => 'hourly',
        'daily' => 'daily',
        'weekly' => 'weekly',
        'monthly' => 'monthly',
        'yearly' => 'yearly',
        'never' => 'never',
    // Form: поля / вкладка / SEO / Фид
    'Feed (news feed, article announcements in RSS, RDF, Atom format)' => 'Feed (news feed, article announcements in RSS, RDF, Atom format)',
    // Form: поля / вкладка / SEO / Директивы индексирования
    'Indexing and serving content directives' => 'Indexing and serving content directives',
    'for all robots' => 'for all robots',
    'metatag "robots"' => 'metatag &laquo;robots&raquo;',
    'robot only "Google"' => 'robot only <span class="g-article__google"><i>G</i><i>o</i><i>o</i><i>g</i><i>l</i><i>e</i></span>',
    'metatag "googlebot"' => 'metatag &laquo;googlebot&raquo;',
    'robot only "Yandex"' => 'robot only  <span class="g-article__yandex"><i>Я</i>ндекс</span>',
    'metatag "yandex"' => 'metatag &laquo;yandex&raquo;',
    'all - no restrictions on indexing and displaying content, equivalent to index, follow;' 
        => '<strong>all</strong> - no restrictions on indexing and displaying content, equivalent to index, follow;',
    'none - is equivalent to noindex, nofollow;' 
        => '<strong>none</strong> - is equivalent to noindex, nofollow;',
    'index - allows robots to index the page;' 
        => '<strong>index</strong> - allows robots to index the page. The page will appear in search results;',
    'noindex - prevents robots from indexing the page;' 
        => '<strong>noindex</strong> - prevents robots from indexing the page. Page will not appear in search results;',
    'follow - allows robots to follow links on the page;' 
        => '<strong>follow</strong> - allows robots to follow links on the page',
    'nofollow - prevents robots from following links on the page.' 
        => '<strong>nofollow</strong> - prevents robots from following links on the page.',
    'more about meta tags: Google, Yandex' 
        => 'more about <a class="g-article__link" target="_blank" href="https://developers.google.com/search/docs/advanced/crawling/special-tags?hl=ru">meta tags Google<a>, <a class="g-article__link" target="_blank" href="https://yandex.ru/support/webmaster/controlling-robot/meta-robots.html">meta tags Яндекс<a>',
    // Form: поля / вкладка / SEO / Особые директивы метатегов
    'Special meta tag directives' => 'Special meta tag directives',
    'directive "noarchive" of meta tag "robots"' => 'directive <strong>noarchive</strong> meta tag <span>&laquo;robots&raquo;</span>',
    'do not show the link to the saved copy in the search results' 
        => 'do not show a link to a saved copy in search results (forbids showing a link to the cache). <span>Яндекс, Google, Yahoo.</span>',
    'directive "noyaca" of meta tag "yandex"' => 'directive <strong>noyaca</strong> meta tag <span>&laquo;yandex&raquo;</span>',
    'ban on using descriptions from Yandex' 
        => 'a ban on the use of a description from Yandex.Catalog when generating a search results snippet. <span>Яндекс.</span>',
    'directive "noydir" of meta tag "robots"' 
        => 'directive <strong>noydir</strong> meta tag <span>&laquo;robots&raquo;</span>',
    'restricting the borrowing of descriptions and site names from the Yahoo directory' 
        => 'limiting the borrowing of descriptions and site names from the Yahoo catalog when generating a search results snippet. <span>Yahoo.</span>',
    'directive "noodp" of meta tag "robots"' 
        => 'directive <strong>noodp</strong> meta tag <span>&laquo;robots&raquo;</span>',
    'limiting the borrowing of site descriptions and names from the Open Directory Project' 
        => 'limit borrowing descriptions and site name from the Open Directory Project (DMOZ directory). <span>Яндекс, Google, Yahoo, Bing.</span>',
    'directive "notranslate" of meta tag "google"' 
        => 'directive <strong>notranslate</strong>  meta tag <span>&laquo;google&raquo;</span>',
    'informs Google that this page should not be translated into other languages' 
        => 'informs Google that this page should not be translated into other languages. <span>Google.</span>',
    'directive "nopagereadaloud" of meta tag "google"' 
        => 'directive <strong>nopagereadaloud</strong> метатега <span>&laquo;google&raquo;</span>',
    'prevents browsers from performing such Google Assistant voice commands' 
        => 'Prevents browsers from using Google Assistant voice commands such as "Read" or "Read this page" to read the text posted on the page. <span>Google.</span>',
    'directive "noimageindex" of meta tag "robots"' 
        => 'directive <strong>noimageindex</strong> meta tag <span>&laquo;robots&raquo;</span>',
    'prohibits indexing images on the page' 
        => 'prohibits indexing images on the page. <span>Google.</span>',
    'directive "nosnippet" of meta tag "robots"' 
        => 'directive <strong>nosnippet</strong>  meta tag <span>&laquo;robots&raquo;</span>',
    'do not show text or video in search results' 
        => 'don\'t show text snippet or video in search results. Static thumbnails (if available) may continue to be displayed if it enhances user experience. This condition is met for all types of search results (Google web search, Google Images, recommendations). <span>Google.</span>',
    'directive "unavailable_after" of meta tag "robots"' 
        => 'directive <strong>unavailable_after</strong> метатега <span>&laquo;robots&raquo;</span>',
    'prevents the page from being shown in search results after the date and time' 
        => 'directive prohibits showing the page in search results after the date and time specified in one of the main formats. <span>Google.</span>',
    'directive "max-snippet" of meta tag "robots"' 
        => 'directive <strong>max-snippet</strong> meta tag <span>&laquo;robots&raquo;</span>',
    'limitation on the number of characters in a text fragment' 
        => 'limitation on the number of characters in a text fragment. <span>Google.</span>',
    'directive defines the maximum size of images' 
        => 'directive defines the maximum size of images. <span>Google.</span>',
    'directive "max-image-preview" of meta tag "robots"' 
        => 'directive <strong>max-image-preview</strong> of meta tag <span>&laquo;robots&raquo;</span>',
    'value' => 'value',
    'no picture' => 'no picture',
    'medium image' => 'medium image',
    'large image' => 'large image',
    'sets a limit on the duration in seconds for a video fragment from a page' 
        => 'sets a limit on the duration in seconds for a video fragment from a page. <span>Google.</span>',
    'directive "max-video-preview" of meta tag "robots"' 
        => 'directive <strong>max-video-preview</strong> of meta tag <span>&laquo;robots&raquo;</span>',
    // Form: поля / анонс
    'Announce' => 'Announce',
    // Form: поля / текст статьи
    'Text' => 'Text',
    // Form: поля / дополнительно
    'Tags' => 'Tags',
    'Labels or tags that will be displayed on the content page as hyperlinks' 
        => 'Labels or tags that will be displayed on the content page as hyperlinks',
    'Additionally' => 'Additionally',
    'Index' => 'Index',
    'Index number' => 'Index number',
    'The ordinal number of the article is used to sort the articles in the list' => 'The ordinal number of the article is used to sort the articles in the list',
    'Number of hits' => 'Number of hits',
    '<span>search</span>' => '<span>search</span>',
    'the article participates in the search on the site' => 'the article participates in the search on the site',
    '<span>sitemap</span>' => '<span>sitemap</span>',
    'the sitemap includes a link to the article' => 'the sitemap includes a link to the article',
    '<span>caching</span>' => '<span>caching</span>',
    // Form:  всплывающие сообщения
    'Unable to delete attachments (files) of the material' => 'Unable to delete attachments (files) of the material.',

    // Grid: контекстное меню записи
    'Edit article' => 'Edit article',
    'Copy article' => 'Copy article',
    // Grid: инструменты
    'Adding a new record "{0}"' => 'Adding a new record "{0}"',
    'Deleting selected records "{0}"' => 'Deleting selected records "{0}"',
    'Deleting all records "{0}"' => 'Deleting all records "{0}"',
    'Do you really want to delete all records "{0}"?' => 'Do you really want to delete all records "{0}"?',
    // Grid: столбцы
    'Header' => 'Header',
    'Category' => 'Category',
    'Type' => 'Type',
    'Slug type' => 'Slug type',
    'Date of publication' => 'Date of publication',
    'Date of announcement' => 'Date of announcement',
    'Caching' => 'Caching',
    'Indexing' => 'Indexing',
    'URL path' => 'URL path',
    'Publishing an article' => 'Publishing an article',
    'View article' => 'View article',
    'the article is not indexed by robots' => 'the article is not indexed by robots',
    'yes' => 'yes',
    'no' => 'no',
    // Grid: всплывающие сообщения / заголовок
    'Show' => 'Show',
    'Hide' => 'Hide',
    // Grid: всплывающие сообщения / текст
    'The record has been hidden' => 'The record has been hidden.',
    'The record has been shown' => 'The record has been shown.',
    'Attachments (files) of materials were partially removed' => 'Attachments (files) of materials were partially removed.',
];