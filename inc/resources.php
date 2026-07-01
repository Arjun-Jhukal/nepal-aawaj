<?php
/**
 * Front-end + admin assets for the Rastriya Aawaj static build.
 */
if (! defined('ABSPATH')) exit;

function na_register_resources() {
    $theme_uri = get_template_directory_uri();
    $ver       = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'na-styles',
        $theme_uri . '/assets/css/styles.css',
        array(),
        $ver
    );

    wp_enqueue_script(
        'na-shared',
        $theme_uri . '/assets/js/shared.min.js',
        array(),
        $ver,
        true
    );
}
add_action('wp_enqueue_scripts', 'na_register_resources');

/**
 * Google Fonts loader — minimised weight list (only 400/500/600/700 are
 * used by the CSS, no system-monospace replacement needed), preconnected
 * and loaded non-blockingly via rel="preload" + onload swap. A <noscript>
 * fallback keeps the page accessible if JS is disabled.
 */
function na_preload_fonts() {
    $href = 'https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700&family=Tiro+Devanagari+Hindi&family=Source+Serif+4:opsz,wght@8..60,400;8..60,700&family=Manrope:wght@400;500;600;700&display=swap';
    echo "\n";
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preload" as="style" href="' . esc_url($href) . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    echo '<noscript><link rel="stylesheet" href="' . esc_url($href) . '"></noscript>' . "\n";
}
add_action('wp_head', 'na_preload_fonts', 1);

function na_admin_resources() {
    wp_enqueue_style(
        'na-admin',
        get_template_directory_uri() . '/assets/css/dashboard-style.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('admin_enqueue_scripts', 'na_admin_resources');

