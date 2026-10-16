<?php
if (!defined('ABSPATH')) exit;

/**
 * Четыре языка в теме.
 *
 * Язык определяется адресом: / — русский, /en/, /kz/, /zh/ — остальные.
 * Перевод берётся из того же ACF-поля с языковым суффиксом: hero_title_en,
 * hero_title_kk, hero_title_zh. Нет перевода — показываем русский, а не пустоту.
 *
 * Шаблоны при этом не меняются: язык подмешан в azk_field/azk_sub/azk_setting.
 */

/** код языка => папка в адресе. У казахского папка kz, код остаётся kk. */
function azk_languages() {
    return [
        'ru' => ['slug' => '',   'html' => 'ru',      'label' => 'Русский', 'short' => 'RU'],
        'en' => ['slug' => 'en', 'html' => 'en',      'label' => 'English', 'short' => 'EN'],
        'kk' => ['slug' => 'kz', 'html' => 'kk',      'label' => 'Қазақша', 'short' => 'KZ'],
        'zh' => ['slug' => 'zh', 'html' => 'zh-Hans', 'label' => '中文',     'short' => '中文'],
    ];
}

/** Текущий язык. */
function azk_lang() {
    static $lang = null;
    if ($lang !== null) return $lang;

    $q = get_query_var('azk_lang');
    if (!$q) {
        // запасной путь: если правила перезаписи ещё не сброшены, берём язык из URI
        $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '', '/');
        $q = $path;
    }
    foreach (azk_languages() as $code => $cfg) {
        if ($cfg['slug'] !== '' && $q === $cfg['slug']) return $lang = $code;
    }
    return $lang = 'ru';
}

/** Адрес языковой версии текущей страницы. */
function azk_lang_url($code) {
    $langs = azk_languages();
    $slug = $langs[$code]['slug'] ?? '';
    return home_url('/' . ($slug ? $slug . '/' : ''));
}

/**
 * Имя поля с учётом языка. Для русского — исходное имя.
 * Остальные языки сначала ищут hero_title_en и только потом падают на hero_title.
 */
function azk_i18n_key($key) {
    $lang = azk_lang();
    return $lang === 'ru' ? $key : $key . '_' . $lang;
}

/** /en/, /kz/, /zh/ — отдельные адреса, отдающие главную на нужном языке. */
add_action('init', function () {
    foreach (azk_languages() as $cfg) {
        if ($cfg['slug'] === '') continue;
        add_rewrite_rule('^' . $cfg['slug'] . '/?$', 'index.php?azk_lang=' . $cfg['slug'], 'top');
    }
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'azk_lang';
    return $vars;
});

/** Языковые адреса показывают ту же главную страницу. */
add_action('pre_get_posts', function ($q) {
    if (is_admin() || !$q->is_main_query()) return;
    if (!get_query_var('azk_lang')) return;
    $front = (int) get_option('page_on_front');
    if ($front) {
        $q->set('page_id', $front);
        $q->is_home = false;
        $q->is_page = true;
        $q->is_singular = true;
    }
});

/**
 * Сброс правил перезаписи.
 *
 * Одного хука активации мало: если тема уже активна (как при обновлении), он
 * не сработает и /en/ отдаст 404. Поэтому держим версию маршрутов в опции и
 * сбрасываем правила, когда она изменилась — один раз, а не на каждый запрос.
 */
define('AZK_ROUTES_VERSION', '2026-10-16-1');

add_action('init', function () {
    if (get_option('azk_routes_version') !== AZK_ROUTES_VERSION) {
        flush_rewrite_rules(false);
        update_option('azk_routes_version', AZK_ROUTES_VERSION);
    }
}, 99);

add_action('after_switch_theme', function () {
    delete_option('azk_routes_version');
});

/**
 * WordPress считает /en/ «неканоническим» адресом главной и уводит на /.
 * Для языковых адресов это выключаем — иначе ни один из них не открыть.
 */
add_filter('redirect_canonical', function ($redirect) {
    return get_query_var('azk_lang') ? false : $redirect;
});

/** <html lang="…"> по текущему языку. */
add_filter('language_attributes', function ($output) {
    $langs = azk_languages();
    $html = $langs[azk_lang()]['html'];
    return preg_replace('/lang="[^"]*"/', 'lang="' . esc_attr($html) . '"', $output, 1);
});

/**
 * Свой canonical вместо вордпрессовского: его rel_canonical не знает про языки
 * и на /en/ выводит адрес главной, из-за чего в head оказывается два canonical.
 */
remove_action('wp_head', 'rel_canonical');

/** canonical + hreflang на все четыре версии. */
function azk_head_links() {
    $langs = azk_languages();
    $cur = azk_lang();
    echo "\n" . '<link rel="canonical" href="' . esc_url(azk_lang_url($cur)) . '">' . "\n";
    foreach ($langs as $code => $cfg) {
        echo '<link rel="alternate" hreflang="' . esc_attr($cfg['html'])
           . '" href="' . esc_url(azk_lang_url($code)) . '">' . "\n";
    }
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url(azk_lang_url('ru')) . '">' . "\n";
}
