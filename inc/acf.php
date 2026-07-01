<?php
/**
 * ACF integration: save/load JSON in /acf-json/ so field groups travel
 * with the theme. ACF (free or Pro 6.0+) is required for blocks to render.
 */
if (! defined('ABSPATH')) exit;

/**
 * Save ACF field groups as JSON inside the theme. Editors who tweak fields
 * in wp-admin get a JSON drop in this folder, ready to commit.
 */
function na_acf_json_save_point($path) {
    $dir = get_template_directory() . '/acf-json';
    if (! is_dir($dir)) {
        wp_mkdir_p($dir);
    }
    return $dir;
}
add_filter('acf/settings/save_json', 'na_acf_json_save_point');

/**
 * Load field groups from the theme's acf-json/. Returning an array of
 * paths lets us keep theme JSON alongside any plugin-provided JSON.
 */
function na_acf_json_load_point($paths) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'na_acf_json_load_point');

/**
 * Render-time admin warning if ACF is missing — blocks ship a fallback
 * notice instead of fataling, but the theme reads cleaner with ACF on.
 */
function na_acf_missing_notice() {
    if (function_exists('acf_register_block_type')) return;
    if (! current_user_can('activate_plugins')) return;
    echo '<div class="notice notice-warning"><p>'
        . esc_html__('Rastriya Aawaj theme: Advanced Custom Fields (6.0+) is required for dynamic blocks to render fully. Static fallbacks are shown until ACF is active.', 'rastriya-aawaj')
        . '</p></div>';
}
add_action('admin_notices', 'na_acf_missing_notice');

/**
 * Safe wrapper around get_field() that survives ACF being deactivated —
 * blocks call this rather than get_field() directly so they degrade
 * gracefully to their static fallback markup.
 *
 * @param string         $key      ACF field name.
 * @param int|string|null $post_id Defaults to current loop post / block context.
 * @param mixed          $default  Returned when ACF is off or value is empty.
 * @return mixed
 */
function na_field($key, $post_id = null, $default = '') {
    if (! function_exists('get_field')) return $default;
    $value = get_field($key, $post_id);
    // null = field not set at all; empty string = blank text. Both fall back.
    // false IS a meaningful stored value (true_false toggle turned off), so let it through.
    if ($value === null || $value === '') return $default;
    return $value;
}

/**
 * Shorthand for reading a Theme Settings options field.
 *
 * @param string $key     ACF field name (without the ts_ prefix).
 * @param mixed  $default Fallback when ACF is off or field is empty.
 * @return mixed
 */
function na_option($key, $default = '') {
    return na_field($key, 'option', $default);
}

/**
 * Register the Theme Settings ACF options page.
 * Requires ACF PRO (acf_add_options_page is a PRO function).
 */
function na_register_options_pages() {
    if (! function_exists('acf_add_options_page')) return;
    acf_add_options_page(array(
        'page_title'  => 'Theme Settings',
        'menu_title'  => 'Theme Settings',
        'menu_slug'   => 'theme-settings',
        'capability'  => 'manage_options',
        'redirect'    => false,
        'icon_url'    => 'dashicons-admin-customizer',
        'position'    => 60,
    ));
}
add_action('acf/init', 'na_register_options_pages');

