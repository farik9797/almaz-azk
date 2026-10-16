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
        if ($val !== null && $val !== '' && $val !== false) return azk_translate_value($val);
    }
    return azk_translate_value($default);
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
        if ($val !== null && $val !== '' && $val !== false) return azk_translate_value($val);
    }
    return azk_translate_value($default);
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
        if ($val !== null && $val !== '' && $val !== false) return azk_translate_value($val);
    }
    return azk_translate_value($default);
}

/**
 * Цвет иконки карточки. В макете он назначен вручную по карточкам, а не выводится
 * из чего-то — поэтому держим соответствие «секция + иконка → цвет» здесь.
 * Незнакомая иконка (клиент выбрал свою) получает жёлтый акцент.
 */
function azk_icon_tone($section, $icon) {
    $map = [
        'advantages'  => ['trending-down' => 'text-accent', 'shield-check' => 'text-leaf',
                          'user-check' => 'text-accent', 'clock' => 'text-navy'],
        'services'    => ['truck' => 'text-accent', 'fuel' => 'text-leaf', 'warehouse' => 'text-accent',
                          'file-check' => 'text-leaf', 'credit-card' => 'text-accent'],
        'phone_cards' => ['phone-call' => 'text-accent', 'briefcase' => 'text-leaf',
                          'fuel' => 'text-leaf', 'credit-card' => 'text-navy-light'],
    ];
    return $map[$section][$icon] ?? 'text-accent';
}

/** Значение поля записи БЕЗ перевода — см. azk_setting_raw(). */
function azk_field_raw($key, $default = '', $post_id = null) {
    if (function_exists('get_field')) {
        $val = get_field($key, $post_id);
        if ($val !== null && $val !== '' && $val !== false) return $val;
    }
    return $default;
}

/** Подполе репитера БЕЗ перевода — см. azk_setting_raw(). */
function azk_sub_raw($key, $default = '') {
    if (function_exists('get_sub_field')) {
        $val = get_sub_field($key);
        if ($val !== null && $val !== '' && $val !== false) return $val;
    }
    return $default;
}

/**
 * Значение поля БЕЗ перевода. Нужно, когда шаблон склеивает фразу из частей:
 * перевести надо всю фразу целиком, а не кусок, уже переведённый по дороге.
 */
function azk_setting_raw($key, $default = '') {
    if (function_exists('get_field')) {
        $val = get_field($key, 'option');
        if ($val !== null && $val !== '' && $val !== false) return $val;
    }
    return $default;
}

/**
 * Запасной перевод: значение пришло по-русски (из поля или из дефолта),
 * а языкового поля для него не завели. Тогда переводим по самому тексту
 * таблицей из inc/translations.php. Так нерусские версии остаются полными,
 * даже если в админке переведены не все поля.
 */
function azk_translate_value($val) {
    if (azk_lang() === 'ru') return $val;
    if (is_string($val)) return azk_translate($val);
    if (is_array($val)) return array_map('azk_translate_value', $val);
    return $val;
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
 * Приводит значение поля-картинки к пригодному URL.
 *
 * Пустой медиа-селектор ACF отдаёт не '', а false (в базе лежит «0»), и такой
 * ответ нельзя просто вывести в src — получается сломанная картинка. Поэтому
 * здесь один набор правил на все изображения: готовый URL отдаём как есть,
 * одно имя файла достраиваем до картинки темы, всё остальное считаем пустым.
 */
function azk_image_src($value, $default_filename) {
    if (is_array($value)) $value = $value['url'] ?? '';          // return_format = array
    if (is_numeric($value) && (int) $value > 0) {                 // return_format = id
        $url = wp_get_attachment_url((int) $value);
        if ($url) return $url;
        $value = '';
    }
    if (is_string($value) && $value !== '' && !is_numeric($value)) {
        return strpos($value, '/') === false
            ? AZK_THEME_URI . '/assets/images/' . $value          // просто имя файла
            : $value;
    }
    return AZK_THEME_URI . '/assets/images/' . $default_filename;
}

/**
 * URL картинки из ACF Image (return_format='url') с дефолтом на файл темы.
 */
function azk_image($key, $default_filename, $post_id = null) {
    return azk_image_src(azk_field($key, '', $post_id), $default_filename);
}
