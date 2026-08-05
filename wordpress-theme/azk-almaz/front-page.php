<?php
/**
 * Template Name: Главная (Front Page)
 */
if (!defined('ABSPATH')) exit;
get_header();
?>

<?php
get_template_part('template-parts/hero');
get_template_part('template-parts/advantages');
get_template_part('template-parts/about');
get_template_part('template-parts/products');
get_template_part('template-parts/services');
get_template_part('template-parts/timeline');
get_template_part('template-parts/azs');
get_template_part('template-parts/promo');
get_template_part('template-parts/faq');
get_template_part('template-parts/contacts');
?>

<?php get_footer(); ?>
