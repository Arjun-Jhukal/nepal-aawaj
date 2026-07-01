<?php
/**
 * Rastriya Aawaj theme bootstrap.
 *
 * Loads in a deliberate order: defaults first (so the helpers in
 * inc/helpers.php can rely on na_get_categories()), then CPTs (registered
 * on `init` priority 5), then ACF + blocks, then importer.
 */
if (! defined('ABSPATH')) exit;

require_once get_template_directory() . '/inc/default.php';
require_once get_template_directory() . '/inc/resources.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/page-formatters.php';
require_once get_template_directory() . '/inc/importer.php';
require_once get_template_directory() . '/inc/youtube.php';
require_once get_template_directory() . '/inc/seo.php';