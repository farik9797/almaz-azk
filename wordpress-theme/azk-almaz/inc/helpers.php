<?php
if (!defined('ABSPATH')) exit;

/**
 * Безопасно читает ACF поле с дефолтом.
 */
function azk_field($key, $default = '', $post_id = null) {
    if (function_exists('get_field')) {
        // Для нерусского языка сначала ищем перевод (hero_title_en), потом исходное поле.
        if (($i18n = azk_i18n_key($key)) !== $key) {
            $val = get_field($i18n, $post_id);
            if ($val !== null && $val !== '' && $val !== false) return $val;
        }
        $val = get_field($key, $post_id);
        if ($val !== null && $val !== '' && $val !== false) return $val;
    }
    return $default;
}

/**
 * Читает поле подстроки внутри активной строки Repeater (между have_rows()/the_row()).
 */
function azk_sub($key, $default = '') {
    if (function_exists('get_sub_field')) {
        if (($i18n = azk_i18n_key($key)) !== $key) {
            $val = get_sub_field($i18n);
            if ($val !== null && $val !== '' && $val !== false) return $val;
        }
        $val = get_sub_field($key);
        if ($val !== null && $val !== '' && $val !== false) return $val;
    }
    return $default;
}

/**
 * Глобальные настройки сайта (Options Page). Без ACF Pro — падает на дефолты.
 */
function azk_setting($key, $default = '') {
    if (function_exists('get_field')) {
        if (($i18n = azk_i18n_key($key)) !== $key) {
            $val = get_field($i18n, 'option');
            if ($val !== null && $val !== '' && $val !== false) return $val;
        }
        $val = get_field($key, 'option');
        if ($val !== null && $val !== '' && $val !== false) return $val;
    }
    return $default;
}

/**
 * tel:-ссылка из отображаемого номера телефона.
 */
function azk_tel($phone) {
    $phone  = (string) $phone;
    $digits = preg_replace('/\D/', '', $phone);
    // Номера на сайте записаны в международном формате — плюс нужно сохранить,
    // иначе с иностранного телефона ссылка tel: не наберётся.
    return strpos($phone, '+') === 0 ? '+' . $digits : $digits;
}

/**
 * URL картинки из ACF Image (return_format='url') с дефолтом на файл темы.
 */
function azk_image($key, $default_filename, $post_id = null) {
    $val = azk_field($key, '', $post_id);
    if (is_string($val) && $val !== '') return $val;
    return AZK_THEME_URI . '/assets/images/' . $default_filename;
}
