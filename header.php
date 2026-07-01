<?php
/**
 * Theme header. Renders the full chrome (utility bar, masthead, category nav,
 * breaking-news ticker, search drawer) and opens <main>.
 *
 * Page templates may set $GLOBALS['na_active_cat'] before calling get_header()
 * to control which cat-nav link gets the red underline.
 */
if (! defined('ABSPATH')) exit;
?><!doctype html>
<html <?php language_attributes('ne'); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to main content', 'rastriya-aawaj'); ?></a>

<header class="site-header" role="banner">
    <?php get_template_part('template-parts/header/utility-bar'); ?>
    <?php get_template_part('template-parts/header/masthead'); ?>
    <?php get_template_part('template-parts/header/cat-nav'); ?>
    <?php get_template_part('template-parts/header/ticker'); ?>
    <?php get_template_part('template-parts/header/search-drawer'); ?>
</header>

<main id="main" role="main" tabindex="-1">
