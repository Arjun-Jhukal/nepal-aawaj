<?php
/**
 * ACF block registration.
 *
 * Every block follows the same contract:
 *  - Render template lives at blocks/<slug>/render.php
 *  - Field group JSON lives in /acf-json/ (location: block == acf/<slug>)
 *  - Uses ACF's legacy acf_register_block_type() so the in-canvas form picks
 *    up ACF Pro's full admin styling (toggle, select2, repeater cards) for
 *    free — block.json + the `acf` key has unreliable iframe CSS injection.
 *
 * Block names register under WordPress as `acf/<slug>` (the `acf/` prefix is
 * added automatically by acf_register_block_type).
 */
if (! defined('ABSPATH')) exit;

/**
 * Add a "Rastriya Aawaj" category to the block inserter so theme blocks
 * group together.
 */
function na_block_category($categories) {
    return array_merge(
        array(
            array(
                'slug'  => 'rastriya-aawaj',
                'title' => __('Rastriya Aawaj', 'rastriya-aawaj'),
                'icon'  => 'megaphone',
            ),
        ),
        $categories
    );
}
add_filter('block_categories_all', 'na_block_category', 10, 1);

/**
 * The full block manifest. `array($title, $description, $icon, $align)` per slug.
 * Add a new block: drop a row here, add blocks/<slug>/render.php + acf-json
 * for it, and it's registered.
 */
function na_block_manifest() {
    return array(
        // News / homepage layout
        'hero'             => array('Hero Grid',          'Lead story plus a 4-item side stack. Used on the homepage top.',                'format-aside',      array('wide','full')),
        'latest-news'      => array('Latest News',        '2x2 featured grid + row list of the most recent posts.',                        'list-view',         array('wide','full')),
        'category-news'    => array('Category Split',     'Two columns split by category, each with a feature + a list.',                  'columns',           array('wide','full')),
        'featured-grid'    => array('Featured Grid',      'Three-column category showcase with a feature card per column.',                'screenoptions',     array('wide','full')),
        'opinion'          => array('Opinion / Columns',  'Four columnist cards with avatar, title, excerpt, meta.',                       'edit',              array('wide','full')),
        'video-section'    => array('Multimedia Strip',   'Featured video plus four mixed-media tiles (video/podcast/gallery/live).',      'video-alt3',        array('wide','full')),
        'video-slider'     => array('Video Slider',       'Horizontal-scroll carousel of video cards.',                                    'controls-play',     array('wide','full')),
        'taxonomy-tabs'    => array('Taxonomy Tabs',      'Tabbed category browser with on-click panel switching.',                        'category',          array('wide','full')),
        'breaking-ticker'  => array('Breaking Ticker',    'Scrolling marquee of breaking-news headlines.',                                 'megaphone',         array('wide','full')),

        // Sidebar / panel blocks
        'archive-sidebar'  => array('Archive Sidebar',    'Right-rail: editor pick, province jump, most-read, ad placeholder.',            'list-view',         array()),
        'aside-featured'   => array('Aside / Featured',   'Sidebar of most-read, ad, trending tags, photo of the day.',                    'format-image',      array('wide','full')),
        'briefs'           => array('News Briefs',        'List of short headlines (manual entries or pulled from selected posts).',       'list-view',         array()),
        'trending'         => array('Trending Tags',      'Tag cloud — auto (most-used tags) or a manual list.',                           'tag',               array()),

        // Utility
        'ad-slot'          => array('Ad Slot',            'Inline ad placeholder. Supports raw ad code or a static banner image.',         'megaphone',         array('wide','full')),
        'contact-info'     => array('Contact Info',       'Address, phone, email, hours, social links and an optional map embed.',         'phone',             array('wide','full')),
        'election-hub'     => array('Election Hub',       'Live election results: party seats + leading-candidate constituency table.',    'awards',            array('wide','full')),

        // About Us — page sections
        'about-intro'        => array('About Us: Intro',        'Eyebrow + heading + lead paragraph for the About page top.',                   'editor-paragraph',  array('wide','full')),
        'about-mission'      => array('About Us: Mission',      'Mission cards: numbered title + body grid.',                                   'awards',            array('wide','full')),
        'about-principles'   => array('About Us: Principles',   'Editorial principles grid — titles + bodies.',                                 'list-view',         array('wide','full')),
        'about-stats'        => array('About Us: Stats',        'Big-number stat strip with labels.',                                           'chart-bar',         array('wide','full')),
        'about-team'         => array('About Us: Team',         'Team / leadership grid with photo, name, role, bio.',                          'groups',            array('wide','full')),
        'about-timeline'     => array('About Us: Timeline',     'Founding milestones with year + title + body.',                                'calendar-alt',      array('wide','full')),
        'about-contact-cta'  => array('About Us: Contact CTA',  'Bottom-of-page CTA panel with up to two buttons.',                             'megaphone',         array('wide','full')),

        // Contact Us — page sections
        'contact-form'       => array('Contact Us: Form',       'Heading + intro + form (shortcode or HTML embed).',                            'email',             array('wide','full')),
        'contact-branches'   => array('Contact Us: Branches',   'Branch / office cards with address, phone, email, hours.',                     'location',          array('wide','full')),
        'contact-hours'      => array('Contact Us: Hours',      'Working hours rows + optional emergency contact line.',                        'clock',             array('wide','full')),
    );
}

/**
 * Register every block via acf_register_block_type so ACF's in-canvas form
 * picks up its full admin styling. Runs on acf/init so it always fires after
 * ACF Pro has booted.
 */
function na_register_acf_blocks() {
    if (! function_exists('acf_register_block_type')) return;

    foreach (na_block_manifest() as $slug => $meta) {
        list($title, $description, $icon, $align) = $meta;

        acf_register_block_type(array(
            'name'            => $slug,
            'title'           => __($title, 'rastriya-aawaj'),
            'description'     => __($description, 'rastriya-aawaj'),
            'render_template' => 'blocks/' . $slug . '/render.php',
            'category'        => 'rastriya-aawaj',
            'icon'            => $icon,
            'keywords'        => array('Rastriya Aawaj', $title),
            'mode'            => 'edit',
            'supports'        => array(
                'align'  => $align ? $align : false,
                'mode'   => false,
                'jsx'    => true,
                'anchor' => true,
            ),
            'example'         => array(
                'attributes' => array(
                    'mode' => 'preview',
                    'data' => array('preview' => true),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'na_register_acf_blocks');

/**
 * Convenience: array of every theme block name (with the acf/ prefix that
 * acf_register_block_type assigns). Used by the importer + page formatters.
 *
 * @return array<int,string>
 */
function na_block_names() {
    static $cache = null;
    if ($cache !== null) return $cache;

    $cache = array();
    foreach (na_block_manifest() as $slug => $_meta) {
        $cache[] = 'acf/' . $slug;
    }
    return $cache;
}
