<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', 'azk_register_acf_fields');

function azk_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;
    azk_acf_options();
    azk_acf_home();
    azk_acf_policies();
}

/* Общий список иконок Lucide, использованных в макете — см. assets/js/theme.js (lucide.createIcons()). */
function azk_icon_choices() {
    return [
        'trending-down' => 'trending-down', 'shield-check' => 'shield-check', 'user-check' => 'user-check', 'clock' => 'clock',
        'building-2' => 'building-2', 'truck' => 'truck', 'microscope' => 'microscope', 'fuel' => 'fuel',
        'navigation' => 'navigation', 'warehouse' => 'warehouse', 'credit-card' => 'credit-card',
        'percent' => 'percent', 'sparkles' => 'sparkles', 'phone-call' => 'phone-call', 'briefcase' => 'briefcase',
    ];
}

/* ---------- Options Page: контакты и реквизиты компании (используются в шапке, подвале, контактах) ---------- */
function azk_acf_options() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Настройки сайта',
            'menu_title' => 'Настройки сайта',
            'menu_slug'  => 'azk-settings',
            'icon_url'   => 'dashicons-admin-generic',
        ]);
    }

    acf_add_local_field_group([
        'key'    => 'group_azk_options',
        'title'  => 'Глобальные настройки — контакты и реквизиты',
        'fields' => [
            ['key' => 'field_azk_opt_phone_cooperation', 'name' => 'phone_cooperation', 'label' => 'Телефон: по вопросам сотрудничества (опт, розница, АЗС)', 'type' => 'text', 'default_value' => '8 775 865 3737'],
            ['key' => 'field_azk_opt_phone_general', 'name' => 'phone_general', 'label' => 'Телефон: офис', 'type' => 'text', 'default_value' => '8 (7252) 357-444'],
            ['key' => 'field_azk_opt_phone_accounting', 'name' => 'phone_accounting', 'label' => 'Телефон: бухгалтерия', 'type' => 'text', 'default_value' => '8 771 061 10 66'],
            ['key' => 'field_azk_opt_address_short', 'name' => 'address_short', 'label' => 'Адрес (кратко, для топбара)', 'type' => 'text', 'default_value' => 'г. Шымкент, проспект Абая, 1А'],
            ['key' => 'field_azk_opt_address_full', 'name' => 'address_full', 'label' => 'Адрес офиса (полный)', 'type' => 'text', 'default_value' => 'г. Шымкент, район Тұран, проспект Абая, 1А'],
            ['key' => 'field_azk_opt_hours_office', 'name' => 'hours_office', 'label' => 'Режим работы офиса', 'type' => 'text', 'default_value' => 'Пн - Пт: 08:00 - 17:00, Сб: 08:00 - 13:00 (Офис)'],
            ['key' => 'field_azk_opt_hours_depot', 'name' => 'hours_depot', 'label' => 'Режим работы АЗС', 'type' => 'text', 'default_value' => 'Круглосуточно, 24/7'],
            ['key' => 'field_azk_opt_bin', 'name' => 'bin', 'label' => 'БИН компании', 'type' => 'text', 'default_value' => '011240001881'],
            ['key' => 'field_azk_opt_legal_entity', 'name' => 'legal_entity', 'label' => 'Юридическое наименование', 'type' => 'text', 'default_value' => 'ТОО «АЗК Алмаз»'],
            ['key' => 'field_azk_opt_founding_year', 'name' => 'founding_year', 'label' => 'Год основания', 'type' => 'text', 'default_value' => '1998'],
            ['key' => 'field_azk_opt_years_count', 'name' => 'years_count', 'label' => 'Лет на рынке (бейдж)', 'type' => 'text', 'default_value' => '25+'],
        ],
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'azk-settings']]],
    ]);
}

/* ---------- Главная страница — по одному репитеру/группе полей на секцию ---------- */
function azk_acf_home() {
    acf_add_local_field_group([
        'key'    => 'group_azk_home',
        'title'  => 'АЗК Алмаз — Главная страница',
        'fields' => [

            // --- Hero ---
            ['key' => 'field_azk_hero_tab', 'name' => 'hero_tab', 'label' => 'Hero', 'type' => 'tab'],
            ['key' => 'field_azk_hero_badge', 'name' => 'hero_badge', 'label' => 'Бейдж над заголовком', 'type' => 'text', 'default_value' => 'ТОО «АЗК Алмаз» • 25+ лет стабильной работы'],
            ['key' => 'field_azk_hero_title', 'name' => 'hero_title', 'label' => 'Заголовок (H1)', 'type' => 'text', 'default_value' => 'Надёжные поставки нефтепродуктов'],
            ['key' => 'field_azk_hero_title_accent', 'name' => 'hero_title_accent', 'label' => 'Заголовок — акцентная часть (жёлтая)', 'type' => 'text', 'default_value' => 'с 1998 года'],
            ['key' => 'field_azk_hero_subtitle', 'name' => 'hero_subtitle', 'label' => 'Подзаголовок', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Оптовая и розничная реализация высококачественного бензина (АИ-92, АИ-95) и дизельного топлива стандарта Евро-5. Собственная нефтебаза в г. Шымкент, автопарк бензовозов и сеть современных АЗС.'],
            ['key' => 'field_azk_hero_image', 'name' => 'hero_image', 'label' => 'Фоновое изображение', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'],

            // --- Advantages ---
            ['key' => 'field_azk_adv_tab', 'name' => 'adv_tab', 'label' => 'Преимущества', 'type' => 'tab'],
            ['key' => 'field_azk_adv_title', 'name' => 'advantages_title', 'label' => 'Заголовок секции', 'type' => 'text', 'default_value' => 'Почему выбирают ТОО «АЗК Алмаз»'],
            ['key' => 'field_azk_adv_subtitle', 'name' => 'advantages_subtitle', 'label' => 'Подзаголовок секции', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'За 25+ лет работы на рынке нефтепродуктов мы выстроили безупречную систему поставок, контроля качества и сервиса.'],
            [
                'key' => 'field_azk_advantages', 'name' => 'advantages', 'label' => 'Карточки преимуществ',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить карточку',
                'default_value' => azk_default('advantages'),
                'sub_fields' => [
                    ['key' => 'field_azk_adv_icon', 'name' => 'icon', 'label' => 'Иконка', 'type' => 'select', 'choices' => azk_icon_choices()],
                    ['key' => 'field_azk_adv_number', 'name' => 'number', 'label' => 'Номер', 'type' => 'text'],
                    ['key' => 'field_azk_adv_card_title', 'name' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
                    ['key' => 'field_azk_adv_card_text', 'name' => 'text', 'label' => 'Текст', 'type' => 'textarea', 'rows' => 3],
                ],
            ],

            // --- About ---
            ['key' => 'field_azk_about_tab', 'name' => 'about_tab', 'label' => 'О компании', 'type' => 'tab'],
            ['key' => 'field_azk_about_title', 'name' => 'about_title', 'label' => 'Заголовок', 'type' => 'text', 'default_value' => 'ТОО «АЗК Алмаз» — устойчивое развитие и глубокий опыт с 1998 года'],
            ['key' => 'field_azk_about_text1', 'name' => 'about_text1', 'label' => 'Абзац 1', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Более 25 лет ТОО «АЗК Алмаз» является ключевым участником рынка нефтепродуктов Южного Казахстана. Мы специализируемся на поставках качественного моторного топлива (АИ-92, АИ-95, ДТ) для промышленных, сельскохозяйственных, транспортных предприятий и розничных автовладельцев.'],
            ['key' => 'field_azk_about_text2', 'name' => 'about_text2', 'label' => 'Абзац 2', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Наличие собственной современной нефтебазы в г. Шымкент, химической лаборатории и автопарка спецтранспорта позволяет гарантировать непрерывность поставок, точный учёт объёмов и строгое соблюдение всех технических стандартов.'],
            [
                'key' => 'field_azk_principles', 'name' => 'principles', 'label' => 'Фундаментальные принципы',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить принцип',
                'default_value' => azk_default('principles'),
                'sub_fields' => [
                    ['key' => 'field_azk_principle_title', 'name' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
                    ['key' => 'field_azk_principle_text', 'name' => 'text', 'label' => 'Текст', 'type' => 'text'],
                ],
            ],
            [
                'key' => 'field_azk_gallery_tabs', 'name' => 'gallery_tabs', 'label' => 'Фото-вкладки (О компании)',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить вкладку',
                'default_value' => array_map(function ($row) {
                    $row['image'] = AZK_THEME_URI . '/assets/images/' . $row['image'];
                    return $row;
                }, azk_default('gallery_tabs')),
                'sub_fields' => [
                    ['key' => 'field_azk_gt_icon', 'name' => 'icon', 'label' => 'Иконка вкладки', 'type' => 'select', 'choices' => azk_icon_choices()],
                    ['key' => 'field_azk_gt_label', 'name' => 'tab_label', 'label' => 'Название вкладки', 'type' => 'text'],
                    ['key' => 'field_azk_gt_image', 'name' => 'image', 'label' => 'Фото', 'type' => 'image', 'return_format' => 'url'],
                    ['key' => 'field_azk_gt_badge', 'name' => 'badge', 'label' => 'Бейдж на фото', 'type' => 'text'],
                    ['key' => 'field_azk_gt_title', 'name' => 'title', 'label' => 'Заголовок на фото', 'type' => 'text'],
                    ['key' => 'field_azk_gt_subtitle', 'name' => 'subtitle', 'label' => 'Подпись на фото', 'type' => 'text'],
                ],
            ],
            [
                'key' => 'field_azk_stats', 'name' => 'stats', 'label' => 'Цифры компании (полоса статистики)',
                'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить показатель',
                'default_value' => azk_default('stats'),
                'sub_fields' => [
                    ['key' => 'field_azk_stat_value', 'name' => 'value', 'label' => 'Значение', 'type' => 'text'],
                    ['key' => 'field_azk_stat_label', 'name' => 'label', 'label' => 'Подпись', 'type' => 'text'],
                ],
            ],

            // --- Products ---
            ['key' => 'field_azk_products_tab', 'name' => 'products_tab', 'label' => 'Продукция', 'type' => 'tab'],
            ['key' => 'field_azk_products_title', 'name' => 'products_title', 'label' => 'Заголовок секции', 'type' => 'text', 'default_value' => 'Качественные нефтепродукты'],
            ['key' => 'field_azk_products_subtitle', 'name' => 'products_subtitle', 'label' => 'Подзаголовок секции', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Все марки топлива поставляются напрямую с ведущих НПЗ и проходят обязательный лабораторный контроль с выдачей Паспорта качества.'],
            [
                'key' => 'field_azk_products', 'name' => 'products', 'label' => 'Карточки продукции',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить продукт',
                'default_value' => azk_default('products'),
                'sub_fields' => [
                    ['key' => 'field_azk_p_id', 'name' => 'id', 'label' => 'Код (для калькулятора)', 'type' => 'text'],
                    ['key' => 'field_azk_p_name', 'name' => 'name', 'label' => 'Название', 'type' => 'text'],
                    ['key' => 'field_azk_p_code', 'name' => 'code', 'label' => 'ГОСТ', 'type' => 'text'],
                    ['key' => 'field_azk_p_description', 'name' => 'description', 'label' => 'Описание', 'type' => 'textarea', 'rows' => 3],
                    [
                        'key' => 'field_azk_p_specs', 'name' => 'specs', 'label' => 'Характеристики',
                        'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить строку',
                        'sub_fields' => [
                            ['key' => 'field_azk_p_spec_label', 'name' => 'label', 'label' => 'Параметр', 'type' => 'text'],
                            ['key' => 'field_azk_p_spec_value', 'name' => 'value', 'label' => 'Значение', 'type' => 'text'],
                        ],
                    ],
                    [
                        'key' => 'field_azk_p_features', 'name' => 'features', 'label' => 'Преимущества (список)',
                        'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить пункт',
                        'sub_fields' => [
                            ['key' => 'field_azk_p_feature', 'name' => 'feature', 'label' => 'Пункт', 'type' => 'text'],
                        ],
                    ],
                    ['key' => 'field_azk_p_price', 'name' => 'price_per_liter', 'label' => 'Цена за литр, ₸ (для калькулятора)', 'type' => 'number'],
                    ['key' => 'field_azk_p_density', 'name' => 'density', 'label' => 'Плотность, кг/л (для калькулятора)', 'type' => 'number', 'step' => '0.001'],
                ],
            ],
            ['key' => 'field_azk_lab_image', 'name' => 'lab_image', 'label' => 'Фото лаборатории (баннер качества)', 'type' => 'image', 'return_format' => 'url'],

            // --- Services ---
            ['key' => 'field_azk_services_tab', 'name' => 'services_tab', 'label' => 'Услуги', 'type' => 'tab'],
            ['key' => 'field_azk_services_title', 'name' => 'services_title', 'label' => 'Заголовок секции', 'type' => 'text', 'default_value' => 'Полный спектр услуг на рынке нефтепродуктов'],
            ['key' => 'field_azk_services_subtitle', 'name' => 'services_subtitle', 'label' => 'Подзаголовок секции', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Обеспечиваем надёжное решение задач любой сложности — от разовых оптовых отгрузок до долгосрочного контрактного обслуживания и хранения.'],
            [
                'key' => 'field_azk_services', 'name' => 'services', 'label' => 'Карточки услуг',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить услугу',
                'default_value' => azk_default('services'),
                'sub_fields' => [
                    ['key' => 'field_azk_s_icon', 'name' => 'icon', 'label' => 'Иконка', 'type' => 'select', 'choices' => azk_icon_choices()],
                    ['key' => 'field_azk_s_title', 'name' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
                    ['key' => 'field_azk_s_description', 'name' => 'description', 'label' => 'Краткое описание', 'type' => 'text'],
                    ['key' => 'field_azk_s_details', 'name' => 'details', 'label' => 'Подробности', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
            ['key' => 'field_azk_fleet_image', 'name' => 'fleet_image', 'label' => 'Фото автопарка (баннер логистики)', 'type' => 'image', 'return_format' => 'url'],

            // --- Timeline ---
            ['key' => 'field_azk_timeline_tab', 'name' => 'timeline_tab', 'label' => 'Схема сотрудничества', 'type' => 'tab'],
            ['key' => 'field_azk_timeline_title', 'name' => 'timeline_title', 'label' => 'Заголовок секции', 'type' => 'text', 'default_value' => '5 шагов до успешной отгрузки'],
            ['key' => 'field_azk_timeline_subtitle', 'name' => 'timeline_subtitle', 'label' => 'Подзаголовок секции', 'type' => 'text', 'default_value' => 'Прозрачный и отлаженный процесс работы от первого обращения до полного документооборота.'],
            [
                'key' => 'field_azk_timeline', 'name' => 'timeline', 'label' => 'Шаги',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить шаг',
                'default_value' => azk_default('timeline'),
                'sub_fields' => [
                    ['key' => 'field_azk_t_number', 'name' => 'number', 'label' => 'Номер', 'type' => 'text'],
                    ['key' => 'field_azk_t_title', 'name' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
                    ['key' => 'field_azk_t_subtitle', 'name' => 'subtitle', 'label' => 'Подзаголовок', 'type' => 'text'],
                    ['key' => 'field_azk_t_text', 'name' => 'text', 'label' => 'Текст', 'type' => 'textarea', 'rows' => 2],
                ],
            ],

            // --- AZS network ---
            ['key' => 'field_azk_azs_tab', 'name' => 'azs_tab', 'label' => 'Сеть АЗС', 'type' => 'tab'],
            ['key' => 'field_azk_azs_title', 'name' => 'azs_title', 'label' => 'Заголовок секции', 'type' => 'text', 'default_value' => 'Фирменная сеть АЗС ALMAZ'],
            ['key' => 'field_azk_azs_subtitle', 'name' => 'azs_subtitle', 'label' => 'Подзаголовок секции', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Современные автозаправочные комплексы на ключевых магистралях Шымкента и Туркестанской области. Круглосуточный точный налив и высокий сервис.'],
            ['key' => 'field_azk_azs_map_image', 'name' => 'azs_map_image', 'label' => 'Фон детальной карточки станции (canopy)', 'type' => 'image', 'return_format' => 'url'],
            [
                'key' => 'field_azk_stations', 'name' => 'stations', 'label' => 'АЗС',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить АЗС',
                'default_value' => azk_default('stations'),
                'sub_fields' => [
                    ['key' => 'field_azk_st_slug', 'name' => 'slug', 'label' => 'Код (напр. azs-1)', 'type' => 'text'],
                    ['key' => 'field_azk_st_label', 'name' => 'label', 'label' => 'Короткая метка на карте', 'type' => 'text'],
                    ['key' => 'field_azk_st_name', 'name' => 'name', 'label' => 'Название', 'type' => 'text'],
                    ['key' => 'field_azk_st_region', 'name' => 'region', 'label' => 'Регион (для фильтра)', 'type' => 'text'],
                    ['key' => 'field_azk_st_address', 'name' => 'address', 'label' => 'Адрес', 'type' => 'text'],
                    ['key' => 'field_azk_st_direction', 'name' => 'direction', 'label' => 'Ориентир', 'type' => 'text'],
                    ['key' => 'field_azk_st_phone', 'name' => 'phone', 'label' => 'Телефон', 'type' => 'text'],
                    ['key' => 'field_azk_st_hours', 'name' => 'hours', 'label' => 'Режим работы', 'type' => 'text'],
                    ['key' => 'field_azk_st_marker_top', 'name' => 'marker_top', 'label' => 'Положение метки на карте: сверху, %', 'type' => 'text'],
                    ['key' => 'field_azk_st_marker_left', 'name' => 'marker_left', 'label' => 'Положение метки на карте: слева, %', 'type' => 'text'],
                    [
                        'key' => 'field_azk_st_fuels', 'name' => 'fuels', 'label' => 'Виды топлива',
                        'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить',
                        'sub_fields' => [['key' => 'field_azk_st_fuel', 'name' => 'fuel', 'label' => 'Топливо', 'type' => 'text']],
                    ],
                    [
                        'key' => 'field_azk_st_amenities', 'name' => 'amenities', 'label' => 'Удобства',
                        'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить',
                        'sub_fields' => [['key' => 'field_azk_st_amenity', 'name' => 'amenity', 'label' => 'Удобство', 'type' => 'text']],
                    ],
                ],
            ],

            // --- Promo ---
            ['key' => 'field_azk_promo_tab', 'name' => 'promo_tab', 'label' => 'Акции', 'type' => 'tab'],
            ['key' => 'field_azk_promo_title', 'name' => 'promo_title', 'label' => 'Заголовок секции', 'type' => 'text', 'default_value' => 'Выгодные акции и бонусные программы на АЗС'],
            [
                'key' => 'field_azk_promo_cards', 'name' => 'promo_cards', 'label' => 'Карточки акций',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить карточку',
                'default_value' => azk_default('promo_cards'),
                'sub_fields' => [
                    ['key' => 'field_azk_pr_icon', 'name' => 'icon', 'label' => 'Иконка', 'type' => 'select', 'choices' => azk_icon_choices()],
                    ['key' => 'field_azk_pr_title', 'name' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
                    ['key' => 'field_azk_pr_text', 'name' => 'text', 'label' => 'Текст', 'type' => 'textarea', 'rows' => 2],
                    ['key' => 'field_azk_pr_tagline', 'name' => 'tagline', 'label' => 'Короткая приписка', 'type' => 'text'],
                ],
            ],

            // --- FAQ ---
            ['key' => 'field_azk_faq_tab', 'name' => 'faq_tab', 'label' => 'Вопросы', 'type' => 'tab'],
            ['key' => 'field_azk_faq_title', 'name' => 'faq_title', 'label' => 'Заголовок секции', 'type' => 'text', 'default_value' => 'Частые вопросы'],
            [
                'key' => 'field_azk_faq_items', 'name' => 'faq_items', 'label' => 'Вопросы и ответы',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить вопрос',
                'default_value' => azk_default('faq'),
                'sub_fields' => [
                    ['key' => 'field_azk_faq_q', 'name' => 'question', 'label' => 'Вопрос', 'type' => 'text'],
                    ['key' => 'field_azk_faq_a', 'name' => 'answer', 'label' => 'Ответ', 'type' => 'textarea', 'rows' => 3],
                ],
            ],

            // --- Contacts ---
            ['key' => 'field_azk_contacts_tab', 'name' => 'contacts_tab', 'label' => 'Контакты', 'type' => 'tab'],
            ['key' => 'field_azk_contacts_title', 'name' => 'contacts_title', 'label' => 'Заголовок секции', 'type' => 'text', 'default_value' => 'Контактная информация и отделы'],
            [
                'key' => 'field_azk_phone_cards', 'name' => 'phone_cards', 'label' => 'Карточки телефонов',
                'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Добавить карточку',
                'default_value' => azk_default('phone_cards'),
                'sub_fields' => [
                    ['key' => 'field_azk_pc_icon', 'name' => 'icon', 'label' => 'Иконка', 'type' => 'select', 'choices' => azk_icon_choices()],
                    ['key' => 'field_azk_pc_department', 'name' => 'department', 'label' => 'Отдел', 'type' => 'text'],
                    ['key' => 'field_azk_pc_title', 'name' => 'title', 'label' => 'Название', 'type' => 'text'],
                    [
                        'key' => 'field_azk_pc_phones', 'name' => 'phones', 'label' => 'Телефоны',
                        'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить номер',
                        'sub_fields' => [['key' => 'field_azk_pc_phone', 'name' => 'phone', 'label' => 'Номер', 'type' => 'text']],
                    ],
                ],
            ],
        ],
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
    ]);
}

/* ---------- Модальное окно: Политика конфиденциальности / Условия использования ---------- */
function azk_acf_policies() {
    acf_add_local_field_group([
        'key'    => 'group_azk_policies',
        'title'  => 'Правовые документы (модальное окно в подвале)',
        'fields' => [
            ['key' => 'field_azk_privacy_title', 'name' => 'privacy_title', 'label' => 'Заголовок: Политика конфиденциальности', 'type' => 'text', 'default_value' => 'Политика конфиденциальности ТОО «АЗК Алмаз»'],
            ['key' => 'field_azk_privacy_body', 'name' => 'privacy_body', 'label' => 'Текст: Политика конфиденциальности', 'type' => 'wysiwyg', 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0, 'default_value' => azk_policy_default('privacy')],
            ['key' => 'field_azk_terms_title', 'name' => 'terms_title', 'label' => 'Заголовок: Условия использования', 'type' => 'text', 'default_value' => 'Условия использования и публичная оферта'],
            ['key' => 'field_azk_terms_body', 'name' => 'terms_body', 'label' => 'Текст: Условия использования', 'type' => 'wysiwyg', 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0, 'default_value' => azk_policy_default('terms')],
        ],
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
    ]);
}

function azk_policy_default($which) {
    if ($which === 'privacy') {
        return '<p><strong>ТОО «АЗК Алмаз» (БИН 011240001881, г. Шымкент)</strong></p>
<p>Настоящий документ определяет политику ТОО «АЗК Алмаз» в отношении обработки и защиты персональных данных пользователей официального веб-сайта компании.</p>
<h4>1. Сбор информации</h4>
<p>Мы собираем только ту персональную информацию (имя, номер телефона), которую пользователь добровольно предоставляет при обращении по телефону, указанному на сайте.</p>
<h4>2. Использование данных</h4>
<p>Предоставленные данные используются исключительно для связи с клиентом, выписки коммерческих предложений, организации доставки нефтепродуктов и предоставления услуг сети АЗС.</p>
<h4>3. Защита информации</h4>
<p>ТОО «АЗК Алмаз» предпринимает все необходимые организационные и технические меры для защиты персональных данных клиентов от несанкционированного доступа, изменения или разглашения.</p>';
    }
    return '<p><strong>ТОО «АЗК Алмаз» (БИН 011240001881, г. Шымкент)</strong></p>
<p>Информация на сайте носит справочный характер и не является публичной офертой в значении статьи 395 Гражданского кодекса Республики Казахстан, если иное прямо не указано.</p>
<h4>1. Использование сайта</h4>
<p>Материалы сайта предназначены для ознакомления с продукцией и услугами ТОО «АЗК Алмаз». Точные цены, объёмы и условия поставки согласовываются индивидуально по телефону или при заключении договора.</p>
<h4>2. Ответственность</h4>
<p>Компания стремится поддерживать актуальность информации на сайте, но не несёт ответственности за возможные технические неточности. Все существенные условия сотрудничества фиксируются в договоре поставки.</p>
<h4>3. Контакты</h4>
<p>По всем вопросам, связанным с содержанием сайта, обращайтесь по телефонам, указанным в разделе «Контакты».</p>';
}

/* ---------- Уведомление, если ACF не установлен ---------- */
add_action('admin_notices', function () {
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="notice notice-warning"><p><strong>АЗК Алмаз:</strong> для редактирования контента установите и активируйте <a href="' . esc_url(admin_url('plugin-install.php?s=advanced+custom+fields&tab=search')) . '">ACF Pro</a>. Без него сайт работает с дефолтным контентом, взятым из утверждённого макета.</p></div>';
    } elseif (!function_exists('acf_add_options_page')) {
        echo '<div class="notice notice-warning"><p><strong>АЗК Алмаз:</strong> обнаружена бесплатная версия ACF. Repeater/Options Page/Gallery работают только в <a href="https://www.advancedcustomfields.com/pro/" target="_blank" rel="noopener">ACF Pro</a> — установите Pro для редактирования карточек и глобальных настроек.</p></div>';
    }
});
