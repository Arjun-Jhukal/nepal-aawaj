<?php
/**
 * SEO + social meta + JSON-LD structured data.
 *
 * One file, two responsibilities:
 *   1. Output meta tags in <head>: description, canonical, robots,
 *      Open Graph, Twitter Card, theme-color, favicon.
 *   2. Emit JSON-LD blocks for Organization, WebSite, Article (single),
 *      BreadcrumbList. Sized to be light — no inline schema bloat for
 *      pages that don't need it.
 *
 * All editor-facing toggles live on the Theme Settings options page
 * (ts_no_index, ts_og_image, ts_organization_name, ts_theme_color,
 * ts_favicon, ts_twitter_handle).
 */
if (! defined('ABSPATH')) exit;

/* ============================================================
   META TAGS (<head>)
   ============================================================ */

/**
 * Build a description for the current request — post excerpt for singular,
 * tagline/description for the homepage, term description for archives.
 */
function na_seo_description() {
    $desc = '';
    if (is_singular()) {
        $post = get_queried_object();
        if ($post) {
            $desc = $post->post_excerpt ?: wp_strip_all_tags($post->post_content);
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        if ($term && ! empty($term->description)) $desc = $term->description;
    } elseif (is_author()) {
        $desc = get_the_author_meta('description', (int) get_queried_object_id());
    } elseif (is_search()) {
        /* translators: %s: search query */
        $desc = sprintf(__('Search results for "%s"', 'rastriya-aawaj'), get_search_query());
    } elseif (is_home() || is_front_page()) {
        $desc = (string) na_option('ts_site_description', get_bloginfo('description'));
    }

    $desc = wp_strip_all_tags((string) $desc);
    $desc = preg_replace('/\s+/u', ' ', $desc);
    return trim(mb_substr($desc, 0, 160));
}

/**
 * Canonical URL for the current request. WordPress already emits one
 * for singular content via rel_canonical(); we extend it to archives,
 * front page, and search.
 */
function na_seo_canonical() {
    if (is_singular()) return get_permalink();
    if (is_front_page() || is_home()) return home_url('/');
    if (is_category() || is_tag() || is_tax()) {
        $link = get_term_link(get_queried_object());
        return is_wp_error($link) ? '' : $link;
    }
    if (is_author()) return get_author_posts_url((int) get_queried_object_id());
    if (is_post_type_archive()) return get_post_type_archive_link(get_query_var('post_type'));
    if (is_search()) return add_query_arg('s', get_search_query(), home_url('/'));
    return '';
}

/**
 * Choose the best og:image — featured image, then the global default,
 * then nothing.
 */
function na_seo_og_image() {
    if (is_singular() && has_post_thumbnail()) {
        $url = get_the_post_thumbnail_url(null, 'full');
        if ($url) return $url;
    }
    $img = na_image_data(na_option('ts_og_image'));
    if ($img) return $img['url'];
    // Fall back to the logo if uploaded.
    $logo = na_image_data(na_option('ts_logo'));
    return $logo ? $logo['url'] : '';
}

/**
 * Emit all meta tags. Runs early enough in <head> that crawlers and
 * the theme-color picker see them on the first parse.
 */
function na_seo_meta() {
    $description = na_seo_description();
    $canonical   = na_seo_canonical();
    $robots      = na_option('ts_no_index', false) ? 'noindex,nofollow' : 'index,follow,max-image-preview:large';
    $title       = wp_get_document_title();
    $og_image    = na_seo_og_image();
    $site_name   = (string) na_option('ts_site_name', get_bloginfo('name'));
    $theme_color = (string) na_option('ts_theme_color', '#DC1F26');
    $type        = is_singular(array('post')) ? 'article' : 'website';
    $twitter     = (string) na_option('ts_twitter_handle', '');

    // Disable WP's built-in canonical so we don't double-emit.
    remove_action('wp_head', 'rel_canonical');

    echo "\n<!-- SEO -->\n";
    if ($description) echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    if ($canonical)   echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";

    if ($theme_color) {
        echo '<meta name="theme-color" content="' . esc_attr($theme_color) . '">' . "\n";
        echo '<meta name="msapplication-TileColor" content="' . esc_attr($theme_color) . '">' . "\n";
    }
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";

    // Open Graph
    echo '<meta property="og:type" content="' . esc_attr($type) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    if ($description) echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    if ($canonical)   echo '<meta property="og:url" content="' . esc_url($canonical) . '">' . "\n";
    if ($site_name)   echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr(str_replace('-', '_', get_locale() ?: 'ne_NP')) . '">' . "\n";
    if ($og_image)    echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";

    if (is_singular(array('post'))) {
        $post = get_queried_object();
        if ($post) {
            echo '<meta property="article:published_time" content="' . esc_attr(get_post_time('c', true, $post)) . '">' . "\n";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_post_modified_time('c', true, $post)) . '">' . "\n";
            $cat = na_primary_category($post->ID);
            if (! empty($cat['name'])) echo '<meta property="article:section" content="' . esc_attr($cat['name']) . '">' . "\n";
        }
    }

    // Twitter Card
    echo '<meta name="twitter:card" content="' . esc_attr($og_image ? 'summary_large_image' : 'summary') . '">' . "\n";
    if ($twitter)     echo '<meta name="twitter:site" content="' . esc_attr('@' . ltrim($twitter, '@')) . '">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    if ($description) echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    if ($og_image)    echo '<meta name="twitter:image" content="' . esc_url($og_image) . '">' . "\n";

    // Favicon — only when an editor uploaded one (WP handles site icon natively otherwise).
    $favicon = na_image_data(na_option('ts_favicon'));
    if ($favicon) {
        echo '<link rel="icon" href="' . esc_url($favicon['url']) . '" type="image/png">' . "\n";
        echo '<link rel="apple-touch-icon" href="' . esc_url($favicon['url']) . '">' . "\n";
    }
    echo "<!-- /SEO -->\n";
}
add_action('wp_head', 'na_seo_meta', 1);

/* ============================================================
   ROBOTS HEADER (X-Robots-Tag) for the no-index toggle
   ============================================================ */

/**
 * Mirror the no-index toggle onto an HTTP response header so headless
 * crawlers / CDNs see it before parsing HTML.
 */
function na_seo_robots_header() {
    if (is_admin() || is_user_logged_in()) return;
    if (! na_option('ts_no_index', false)) return;
    if (headers_sent()) return;
    header('X-Robots-Tag: noindex, nofollow', true);
}
add_action('send_headers', 'na_seo_robots_header');

/* ============================================================
   JSON-LD STRUCTURED DATA
   ============================================================ */

/**
 * Emit Organization + WebSite schema on every page, plus Article schema
 * on single posts and BreadcrumbList anywhere $crumbs is registered.
 */
function na_seo_json_ld() {
    $home  = home_url('/');
    $name  = (string) na_option('ts_organization_name', na_option('ts_site_name', get_bloginfo('name')));
    $logo  = na_image_data(na_option('ts_logo'));

    $org = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        '@id'      => $home . '#organization',
        'name'     => $name,
        'url'      => $home,
    );
    if ($logo) {
        $org['logo'] = array(
            '@type' => 'ImageObject',
            'url'   => $logo['url'],
        );
    }
    $socials = array();
    foreach (array('ts_facebook_url', 'ts_twitter_url', 'ts_youtube_url', 'ts_instagram_url') as $opt) {
        $url = na_option($opt);
        if ($url) $socials[] = $url;
    }
    if ($socials) $org['sameAs'] = $socials;

    $website = array(
        '@context'      => 'https://schema.org',
        '@type'         => 'WebSite',
        '@id'           => $home . '#website',
        'url'           => $home,
        'name'          => (string) na_option('ts_site_name', get_bloginfo('name')),
        'description'   => (string) na_option('ts_site_description', get_bloginfo('description')),
        'publisher'     => array('@id' => $home . '#organization'),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => $home . '?s={search_term_string}',
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );

    $graph = array($org, $website);

    if (is_singular(array('post'))) {
        $post = get_queried_object();
        if ($post) {
            $article = array(
                '@type'         => 'NewsArticle',
                '@id'           => get_permalink($post) . '#article',
                'headline'      => get_the_title($post),
                'datePublished' => get_post_time('c', true, $post),
                'dateModified'  => get_post_modified_time('c', true, $post),
                'mainEntityOfPage' => get_permalink($post),
                'url'           => get_permalink($post),
                'author'        => array(
                    '@type' => 'Person',
                    'name'  => get_the_author_meta('display_name', (int) $post->post_author),
                    'url'   => get_author_posts_url((int) $post->post_author),
                ),
                'publisher'     => array('@id' => $home . '#organization'),
            );
            $thumb = get_the_post_thumbnail_url($post, 'full');
            if ($thumb) $article['image'] = $thumb;
            $excerpt = $post->post_excerpt ?: wp_trim_words(wp_strip_all_tags($post->post_content), 30, '');
            if ($excerpt) $article['description'] = $excerpt;
            $cat = na_primary_category($post->ID);
            if (! empty($cat['name'])) $article['articleSection'] = $cat['name'];
            $graph[] = $article;
        }
    }

    $breadcrumbs = na_breadcrumbs_data();
    if (! empty($breadcrumbs) && count($breadcrumbs) > 1) {
        $items = array();
        $pos = 1;
        foreach ($breadcrumbs as $crumb) {
            $entry = array(
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => $crumb['label'],
            );
            if (! empty($crumb['href'])) $entry['item'] = $crumb['href'];
            $items[] = $entry;
        }
        $graph[] = array(
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        );
    }

    $payload = array(
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    );

    echo "\n<script type=\"application/ld+json\">"
        . wp_json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        . "</script>\n";
}
add_action('wp_head', 'na_seo_json_ld', 5);

/* ============================================================
   BREADCRUMBS — data source for both schema + on-page render
   ============================================================ */

/**
 * Return the breadcrumb trail for the current request as an array of
 * `['label' => '...', 'href' => '...']`. Last entry has no href (current page).
 *
 * @return array<int,array{label:string,href:string}>
 */
function na_breadcrumbs_data() {
    $crumbs = array(array('label' => __('गृहपृष्ठ', 'rastriya-aawaj'), 'href' => home_url('/')));

    if (is_front_page() || is_home()) {
        return array();
    }

    if (is_singular('post')) {
        $cat = na_primary_category(get_queried_object_id());
        if (! empty($cat['link'])) {
            $crumbs[] = array('label' => $cat['np'] ?: $cat['name'], 'href' => $cat['link']);
        }
        $crumbs[] = array('label' => get_the_title(), 'href' => '');
        return $crumbs;
    }

    if (is_singular()) {
        $crumbs[] = array('label' => get_the_title(), 'href' => '');
        return $crumbs;
    }

    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        if ($term && $term->parent) {
            $parent = get_term($term->parent, $term->taxonomy);
            if ($parent && ! is_wp_error($parent)) {
                $crumbs[] = array('label' => $parent->name, 'href' => get_term_link($parent));
            }
        }
        if ($term) $crumbs[] = array('label' => $term->name, 'href' => '');
        return $crumbs;
    }

    if (is_author()) {
        $crumbs[] = array('label' => __('लेखकहरू', 'rastriya-aawaj'), 'href' => '');
        $crumbs[] = array('label' => get_the_author_meta('display_name', (int) get_queried_object_id()), 'href' => '');
        return $crumbs;
    }

    if (is_search()) {
        $crumbs[] = array('label' => sprintf(__('Search: %s', 'rastriya-aawaj'), get_search_query()), 'href' => '');
        return $crumbs;
    }

    if (is_404()) {
        $crumbs[] = array('label' => __('404', 'rastriya-aawaj'), 'href' => '');
        return $crumbs;
    }

    return $crumbs;
}

/**
 * Render on-page breadcrumbs. Uses the same crumb data the JSON-LD uses,
 * so HTML + schema stay in sync.
 *
 * Templates can call: <?php na_breadcrumbs(); ?>
 */
function na_breadcrumbs() {
    $crumbs = na_breadcrumbs_data();
    if (count($crumbs) < 2) return;

    $last = count($crumbs) - 1;
    echo '<nav class="crumbs" aria-label="' . esc_attr__('Breadcrumb', 'rastriya-aawaj') . '">';
    foreach ($crumbs as $i => $c) {
        if ($i > 0) echo '<span class="sep" aria-hidden="true">›</span> ';
        if ($i !== $last && ! empty($c['href'])) {
            echo '<a href="' . esc_url($c['href']) . '">' . esc_html($c['label']) . '</a>';
        } else {
            echo '<span aria-current="page">' . esc_html($c['label']) . '</span>';
        }
    }
    echo '</nav>';
}
