<?php
/**
 * Page formatters — return the serialized block markup used as post_content
 * for each canonical theme page.
 *
 * No page templates: every page is rendered by page.php with the_content().
 * Editors compose pages entirely from blocks in Gutenberg. Each formatter
 * seeds a sensible starting block sequence; editors can reorder/remove.
 */
if (! defined('ABSPATH')) exit;

/**
 * Build a single Gutenberg block comment for one of our theme blocks.
 *
 * @param string $name  Block name without namespace (e.g. "hero").
 * @param array  $attrs Block attributes (typically empty — ACF stores its
 *                      own data keyed by post/block id).
 */
function na_block($name, $attrs = array()) {
    // Blocks registered via acf_register_block_type live under the `acf/` namespace.
    $full = strpos($name, '/') === false ? "acf/{$name}" : $name;
    $json = empty($attrs) ? '' : ' ' . wp_json_encode($attrs);
    return "<!-- wp:{$full}{$json} /-->\n\n";
}

/**
 * Homepage layout — the default block sequence the importer seeds.
 */
function na_format_home() {
    // No breaking-ticker here — the header already renders one via
    // template-parts/header/ticker.php. Drop one block in if you want a
    // second ticker further down the page.
    return na_block('hero')
        . na_block('latest-news')
        . na_block('category-news', array('variant' => 'politics-economy'))
        . na_block('video-section')
        . na_block('featured-grid')
        . na_block('opinion')
        . na_block('category-news', array('variant' => 'world'))
        . na_block('aside-featured');
}

/**
 * About page — seeded with the seven About Us section blocks.
 */
function na_format_about() {
    return na_block('about-intro')
        . na_block('about-mission')
        . na_block('about-principles')
        . na_block('about-stats')
        . na_block('about-team')
        . na_block('about-timeline')
        . na_block('about-contact-cta');
}

/**
 * Contact page — seeded with the three Contact Us section blocks.
 */
function na_format_contact() {
    return na_block('contact-info')
        . na_block('contact-branches')
        . na_block('contact-form')
        . na_block('contact-hours');
}

/**
 * Privacy / Terms — empty by default. Editors fill them with core blocks or
 * the legal_sections ACF repeater can still be used for structured rendering.
 */
function na_format_legal($which = 'privacy') {
    return '';
}

/**
 * Lookup table: slug => formatter callable. The importer iterates this to
 * know which pages to create and how to fill them. No `template` keys — pages
 * use the default page.php.
 *
 * @return array<string, array{title:string, formatter:callable}>
 */
function na_page_blueprints() {
    return array(
        'home' => array(
            'title'     => 'Home',
            'formatter' => 'na_format_home',
        ),
        'about' => array(
            'title'     => 'About Us',
            'formatter' => 'na_format_about',
        ),
        'contact' => array(
            'title'     => 'Contact',
            'formatter' => 'na_format_contact',
        ),
        'privacy' => array(
            'title'     => 'Privacy Policy',
            'formatter' => function () { return na_format_legal('privacy'); },
        ),
        'terms' => array(
            'title'     => 'Terms of Service',
            'formatter' => function () { return na_format_legal('terms'); },
        ),
    );
}
