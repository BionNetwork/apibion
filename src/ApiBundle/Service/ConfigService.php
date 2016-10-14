<?php

namespace ApiBundle\Service;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ConfigService
{
    /**
     * Get localized strings for UI
     *
     * @param $locale
     * @return array
     * @throws \ErrorException
     */
    public function getUiStrings($locale)
    {
        switch ($locale) {
            case 'en':
                return $this->enStrings;
            case 'ru':
                return $this->ruStrings;
            default:
                throw new HttpException(400, "No strings for locale '$locale'");
        }
    }

    private $enStrings = [
        'defaultStrings' =>
            [
                'all' => 'See all',
                'back' => 'Back',
                'currency' => 'usd'
            ],
        'header' =>
            [
                'menu' =>
                    [
                        'desktop' => 'Dashboards',
                        'myWidgets' => 'My widgets',
                        'widgetsStore' => 'Widgets store',
                        'language' => 'Русский',
                        'widgetType' => 'Widget type',
                        'readyBtn' => 'Ready',
                        'notActivatedBtn' => 'Not activated',
                        'updateBtn' => 'Update',
                        'purchasesBtn' => 'Purchased',
                        'savedBtn' => 'Saved ',
                        'menu' => 'Menu',
                    ],
                'headerSearch' =>
                    [
                        'searchByWidgetsStore' => 'Search by widgets store',
                        'searchByMyWidgets' => 'Search by my widgets',
                        'searchByDashbords' => 'Search by dashbords',
                        'searchInput' => '',
                    ],
            ],
        'cardItem' =>
            [
                'dataActivation' => 'Data activation',
                'description' => 'Details',
                'descriptionUnder' => 'Description',
                'customerReviews' => 'Reviews',
                'customerReview' => 'Customer review',
                'moreWidgets' => 'Related',
                'autor' => 'Seller',
                'dateOfChange' => 'Version',
                'share' => 'Share',
                'addToFavourites' => 'Add to wish list',
                'formula' => 'Formula',
                'formulasArguments' => 'Formulas\' arguments',
                'customerRatings' => 'Customer ratings',
                'ratings' => 'ratings',
                'clickToAssess' => 'Tap to rate',
                'publish' => 'Write a review',
                'leaveFeedback' => 'Publish',
                'getALink' => 'Get a link',
                'title' => '"Title": ',
                'enterText' => 'Enter text',
                'addToDeffered' => 'Added to wish list',
                'close' => 'Close',
                'widgetPurchase' => 'Widget purchase',
                'buy' => 'Buy',
                'cancel' => 'Cancel',
                'selectPaymentMethod' => 'Select payment method',
                'change' => 'Сhange',
            ],
        'listBindings' =>
            [
                'argumentsLB' => 'Arguments',
                'dataSource' => 'Data source',
                'chart' => 'Chart',
                'downloadedSources' => 'Downloaded sources',
                'dragAndAdd' => 'Drag the file to the selected area to add',
            ],
        'widgets_store' => [
            'widget_type' => 'Widget type',
            'all_widget_types' => 'All types of widgets',
            'see_all' => 'See all',
            'clear_all_filters' => 'Clear all filters',
            'all_price' => 'All price',
            'free' => 'Free',
            'less_10_usd' => '< $10',
            'more_10_usd' => '> $10',
            'search_by_widgets_store' => 'Search by widgets store',
            'customer_ratings' => 'Customer ratings',
            'ratings' => 'Ratings',
            'write_a_review' => 'Write a review',
            'tap_to_rate' => 'Tap to rate',
            'reviews' => 'Reviews',
            'customer_review' => 'Customer review',
            'tap_to_rate_modal' => 'Tap to rate',
            'publish' => 'Publish',
            'title' => 'Title',
            'enter_text' => 'Enter text',
        ],
        'category_banners' => [
            'marketing' => 'Marketing',
            'strategic_management' => 'Strategic management',
            'monitoring_and_analytics' => 'Monitoring and Analytics',
            'management_reporting' => 'Management reporting',
            'for_manager' => 'For manager',
            'warehouse' => 'Warehouse',
            'marketing_and_advertising' => 'Marketing and Advertising ',
            'personnel' => 'Personnel',
            'sales_and_marketing' => 'Sales and marketing',
        ],
        'dashboard' => [
            'choose' => 'Choose',
            'open' => 'Open',
        ],
    ];

    private $ruStrings = [
        'defaultStrings' =>
            [
                'all' => 'Все',
                'back' => 'Назад',
                'currency' => 'руб'
            ],
        'header' =>
            [
                'menu' =>
                    [
                        'desktop' => 'Рабочие столы',
                        'myWidgets' => 'Мои приложения',
                        'widgetsStore' => 'Магазин приложений',
                        'language' => 'English',
                        'widgetType' => 'Тип карточек',
                        'readyBtn' => 'Готовы к работе',
                        'notActivatedBtn' => 'Незаполненные',
                        'updateBtn' => 'Обновление',
                        'purchasesBtn' => 'Покупки',
                        'savedBtn' => 'Отложенные',
                        'menu' => 'Меню',
                    ],
                'headerSearch' =>
                    [
                        'searchByWidgetsStore' => 'Поиск по магазину',
                        'searchByMyWidgets' => 'Поиск по моим карточкам',
                        'searchByDashbords' => 'Поиск по рабочему столу',
                        'searchInput' => '',
                    ],
            ],
        'cardItem' =>
            [
                'dataActivation' => 'Подключить данные',
                'description' => 'Подробнее',
                'descriptionUnder' => 'Описание',
                'customerReviews' => 'Отзывы',
                'customerReview' => 'Отзыв',
                'moreWidgets' => 'Похожие',
                'autor' => 'Автор',
                'dateOfChange' => 'Дата изменения',
                'share' => 'Дополнительно',
                'addToFavourites' => 'Добавить в отложенные',
                'formula' => 'Формула',
                'formulasArguments' => 'Аргументы формулы',
                'customerRatings' => 'Средний рейтинг',
                'ratings' => 'оценок',
                'clickToAssess' => 'Нажмите чтобы оценить карточку',
                'publish' => 'Оценить карточку',
                'leaveFeedback' => 'Оставить отзыв',
                'getALink' => 'Получить ссылку',
                'title' => 'Заголовок: ',
                'enterText' => 'Текст отзыва',
                'addToDeffered' => 'Добавлено в отложенные',
                'close' => 'Закрыть',
                'widgetPurchase' => 'Покупка карточки',
                'buy' => 'Купить',
                'cancel' => 'Отмена',
                'selectPaymentMethod' => 'Выберите способ оплаты',
                'change' => 'Изменить',
            ],
        'listBindings' =>
            [
                'argumentsLB' => 'Аргументы',
                'dataSource' => 'Подключить данные',
                'chart' => 'График',
                'downloadedSources' => 'Загруженные источники',
                'dragAndAdd' => 'Перетащите файл в выделеную область для добавления',
            ],
        'widgets_store' => [
            'widget_type' => 'Тип карточек',
            'all_widget_types' => 'Все типы приложений',
            'see_all' => 'Все',
            'clear_all_filters' => 'Сбросить все фильтры',
            'all_price' => 'Любая цена',
            'free' => 'Бесплатно',
            'less_10_usd' => '< 100 руб.',
            'more_10_usd' => '> 100 руб.',
            'search_by_widgets_store' => 'Поиск по магазину',
            'customer_ratings' => 'Средний рейтинг',
            'ratings' => 'оценок',
            'write_a_review' => 'Оценить приложение',
            'tap_to_rate' => 'Нажмите чтобы оценить приложение',
            'reviews' => 'Отзывы',
            'customer_review' => 'Отзыв( название модального окна)',
            'tap_to_rate_modal' => 'Оценить приложение',
            'publish' => 'Оставить отзыв (кнопка на форме отзыва)',
            'title' => 'Заголовок',
            'enter_text' => 'Текст отзыва',
        ],
        'category_banners' => [
            'marketing' => 'Маркетинг',
            'strategic_management' => 'Стратегический менеджмент',
            'monitoring_and_analytics' => 'Мониторинг и аналитика',
            'management_reporting' => 'Управленческая отчетность',
            'for_manager' => 'Руководителю',
            'warehouse' => 'Склад',
            'marketing_and_advertising' => 'Маркетинг и реклама',
            'personnel' => 'Персонал',
            'sales_and_marketing' => 'Продажа и сбыт',
        ],
        'dashboard' => [
            'choose' => 'Действия',
            'open' => 'Раскрыть',
        ],
    ];
}
