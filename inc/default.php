<?php
/**
 * Theme supports + menu registration.
 */
if (! defined('ABSPATH')) exit;

function na_theme_support() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
}
add_action('after_setup_theme', 'na_theme_support');

/**
 * Allow SVG + WebP uploads (matches the design's inline-SVG icon set).
 */
function na_mime_types($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'na_mime_types');

/**
 * Menus the static build wires in by hand today; ACF-driven nav comes later.
 */
function na_register_menus() {
    register_nav_menu('primary',    __('Primary Category Nav', 'rastriya-aawaj'));
    register_nav_menu('footer_one', __('Footer Column 1',      'rastriya-aawaj'));
    register_nav_menu('footer_two', __('Footer Column 2',      'rastriya-aawaj'));
    register_nav_menu('footer_meta',__('Footer Meta Links',    'rastriya-aawaj'));
}
add_action('after_setup_theme', 'na_register_menus');

/**
 * Mirror WP's `current-menu-item` flag onto the <a> so the theme's existing
 * `.active` styles still apply when nav comes from Appearance → Menus.
 */
function na_nav_active_link_class($atts, $item) {
    if (! empty($item->current) || in_array('current-menu-item', (array) $item->classes, true)) {
        $atts['class'] = trim((isset($atts['class']) ? $atts['class'] . ' ' : '') . 'active');
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'na_nav_active_link_class', 10, 2);

/**
 * Static categories used by header.php cat-nav and footer.php columns.
 * Lifted from design_handoff_rastriya_aawaj/reference/shared.js (CATEGORIES).
 * Once we move to ACF/taxonomies, this becomes a wp_get_terms() call.
 *
 * @return array<array{slug:string,np:string,en:string}>
 */
function na_get_categories() {
    return array(
        array('slug' => 'politics',      'np' => 'राजनीति',      'en' => 'Politics'),
        array('slug' => 'economy',       'np' => 'अर्थ',          'en' => 'Economy'),
        array('slug' => 'world',         'np' => 'विश्व',         'en' => 'World'),
        array('slug' => 'sports',        'np' => 'खेलकुद',        'en' => 'Sports'),
        array('slug' => 'entertainment', 'np' => 'मनोरञ्जन',      'en' => 'Entertainment'),
        array('slug' => 'tech',          'np' => 'प्रविधि',       'en' => 'Tech'),
        array('slug' => 'opinion',       'np' => 'विचार',         'en' => 'Opinion'),
        array('slug' => 'health',        'np' => 'स्वास्थ्य',     'en' => 'Health'),
        array('slug' => 'culture',       'np' => 'संस्कृति',      'en' => 'Culture'),
        array('slug' => 'education',     'np' => 'शिक्षा',        'en' => 'Education'),
        array('slug' => 'multimedia',    'np' => 'मल्टिमिडिया',   'en' => 'Multimedia'),
    );
}

/**
 * Pretty link for a category by slug. Uses the real category permalink
 * (so /sports works once permalinks are enabled), and falls back to the
 * query-string form if the term doesn't exist in the DB yet.
 *
 * @param string $slug Category slug.
 * @return string URL.
 */
function na_cat_link($slug) {
    $term = get_term_by('slug', $slug, 'category');
    if ($term && ! is_wp_error($term)) {
        $link = get_term_link($term);
        if (! is_wp_error($link)) return $link;
    }
    return home_url('/?cat=' . $slug);
}

/**
 * Strip /category/ from category permalinks → /sports instead of
 * /category/sports. Paired with explicit rewrite rules below so the
 * shorter URL actually resolves.
 */
function na_strip_category_base($url) {
    return str_replace('/category/', '/', $url);
}
add_filter('term_link', 'na_strip_category_base', 10, 1);

/**
 * Register a `^slug/?$` rewrite for every existing category term so the
 * stripped URL resolves to the correct archive. Visit Settings → Permalinks
 * once after enabling pretty permalinks to flush — required for any new
 * categories too.
 */
function na_category_pretty_rewrites() {
    $terms = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));
    if (is_wp_error($terms) || ! $terms) return;
    foreach ($terms as $term) {
        add_rewrite_rule(
            '^' . preg_quote($term->slug, '/') . '/?$',
            'index.php?category_name=' . $term->slug,
            'top'
        );
        add_rewrite_rule(
            '^' . preg_quote($term->slug, '/') . '/page/([0-9]+)/?$',
            'index.php?category_name=' . $term->slug . '&paged=$matches[1]',
            'top'
        );
    }
}
add_action('init', 'na_category_pretty_rewrites');

/**
 * Auto-flush rewrite rules whenever a category is added/edited/deleted so
 * editors don't have to revisit Settings → Permalinks every time.
 */
function na_flush_on_category_change() {
    flush_rewrite_rules(false);
}
add_action('created_category', 'na_flush_on_category_change');
add_action('edited_category',  'na_flush_on_category_change');
add_action('delete_category',  'na_flush_on_category_change');

/**
 * Which cat-nav item should render `.active`. Page templates set
 * $GLOBALS['na_active_cat'] = 'politics' (or similar) before get_header().
 * Defaults to 'home' for the homepage; '' for pages with no active state.
 */
function na_active_cat() {
    if (isset($GLOBALS['na_active_cat'])) {
        return (string) $GLOBALS['na_active_cat'];
    }
    if (is_front_page() || is_home()) return 'home';
    return '';
}

/**
 * Brand SVG mark (mountain + microphone + soundwaves).
 * Lifted from shared.js LOGO_SVG.
 */
function na_brand_svg() {
    return '<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-label="Rastriya Aawaj">'
        . '<path d="M32 6 L8 56 L20 56 L24 48 L40 48 L44 56 L56 56 Z" fill="#DC1F26"/>'
        . '<path d="M32 6 L26 18 L29 18 L31 14 L33 17 L35 13 L38 18 L32 6 Z" fill="#FFFFFF"/>'
        . '<path d="M27 42 L37 42 L34 36 L30 36 Z" fill="#FAFAF7"/>'
        . '<rect x="27" y="28" width="10" height="14" rx="5" fill="#0F0F10"/>'
        . '<line x1="29" y1="32" x2="35" y2="32" stroke="#fff" stroke-width="0.5" opacity="0.4"/>'
        . '<line x1="29" y1="35" x2="35" y2="35" stroke="#fff" stroke-width="0.5" opacity="0.4"/>'
        . '<line x1="29" y1="38" x2="35" y2="38" stroke="#fff" stroke-width="0.5" opacity="0.4"/>'
        . '<path d="M22 30 Q19 35 22 40" stroke="#DC1F26" stroke-width="1.5" fill="none" opacity="0.7"/>'
        . '<path d="M19 28 Q15 35 19 42" stroke="#DC1F26" stroke-width="1.2" fill="none" opacity="0.4"/>'
        . '<path d="M42 30 Q45 35 42 40" stroke="#DC1F26" stroke-width="1.5" fill="none" opacity="0.7"/>'
        . '<path d="M45 28 Q49 35 45 42" stroke="#DC1F26" stroke-width="1.2" fill="none" opacity="0.4"/>'
        . '</svg>';
}

/**
 * Inline SVG icon set. Lifted from shared.js ICONS object.
 * Usage: na_icon('search'); na_icon('fb');
 */
function na_icon($name) {
    static $icons = null;
    if ($icons === null) {
        $icons = array(
            'search'   => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>',
            'menu'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="18" y2="18"/></svg>',
            'moon'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>',
            'bell'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>',
            'home'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>',
            'fb'       => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>',
            'tw'       => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            'yt'       => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
            'ig'       => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37zM17.5 6.5h.01"/></svg>',
            'whatsapp' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413"/></svg>',
            'arrow'    => '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>',
            'email'    => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 17a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/><polyline points="22,7 12,13 2,7"/></svg>',
            'phone'    => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
            'pin'      => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
        );
    }
    return $icons[$name] ?? '';
}


add_filter('robots_txt', function ($output, $public) {
    if ($public) {
        $output .= "Sitemap: https://rastriyaaawaj.com/tools/sitemap.xml\n";
    }
    return $output;
}, 10, 2);