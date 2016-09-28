<?php

namespace ApiBundle\Service;

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
                throw new \ErrorException("No strings for locale '$locale'");
        }
    }

    private $enStrings = [
        'defaultStrings' =>
            [
                'all' => 'See all',
                'back' => 'Back',
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
        'listBindgings' =>
            [
                'argumentsLB' => 'Arguments',
                'dataSource' => 'Data source',
                'chart' => 'Chart',
                'downloadedSources' => 'Downloaded sources',
                'dragAndAdd' => 'Drag the file to the selected area to add',
            ],
    ];

    private $ruStrings = [
        'defaultStrings' =>
            [
                'all' => 'Все',
                'back' => 'Назад',
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
        'listBindgings' =>
            [
                'argumentsLB' => 'Аргументы',
                'dataSource' => 'Подключить данные',
                'chart' => 'График',
                'downloadedSources' => 'Загруженные источники',
                'dragAndAdd' => 'Перетащите файл в выделеную область для добавления',
            ],
    ];
}
