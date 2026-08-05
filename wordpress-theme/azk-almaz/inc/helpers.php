<?php
if (!defined('ABSPATH')) exit;

/**
 * Безопасно читает ACF поле с дефолтом.
 */
function azk_field($key, $default = '', $post_id = null) {
    if (function_exists('get_field')) {
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
        $val = get_field($key, 'option');
        if ($val !== null && $val !== '' && $val !== false) return $val;
    }
    return $default;
}

/**
 * tel:-ссылка из отображаемого номера телефона.
 */
function azk_tel($phone) {
    return preg_replace('/\D/', '', (string) $phone);
}

/**
 * URL картинки из ACF Image (return_format='url') с дефолтом на файл темы.
 */
function azk_image($key, $default_filename, $post_id = null) {
    $val = azk_field($key, '', $post_id);
    if (is_string($val) && $val !== '') return $val;
    return AZK_THEME_URI . '/assets/images/' . $default_filename;
}
