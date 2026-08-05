<?php
if (!defined('ABSPATH')) exit;

define('AZK_THEME_VER', '1.0.0');
define('AZK_THEME_DIR', get_template_directory());
define('AZK_THEME_URI', get_template_directory_uri());

require_once AZK_THEME_DIR . '/inc/helpers.php';
require_once AZK_THEME_DIR . '/inc/defaults.php';
require_once AZK_THEME_DIR . '/inc/acf-fields.php';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', ['height' => 60, 'width' => 200, 'flex-height' => true, 'flex-width' => true]);
    register_nav_menus(['primary' => 'Главное меню']);
});

/* ---------- Enqueue ----------
   Tailwind CDN + its config, and the Lucide icon script, are printed directly in
   header.php's <head> (same load order as the approved static HTML) rather than
   through wp_enqueue_script — the Tailwind CDN script must run before any markup
   is scanned, which the standard footer-script queue does not guarantee. */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('azk-google-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', [], null);
    wp_enqueue_style('azk-main', AZK_THEME_URI . '/assets/css/theme.css', [], AZK_THEME_VER);
    wp_enqueue_script('azk-theme', AZK_THEME_URI . '/assets/js/theme.js', [], AZK_THEME_VER, true);
});
