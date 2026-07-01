<?php
/**
 * Cross-cutting helpers used by templates, blocks, and the importer.
 *
 * - na_responsive_img(): srcset-aware <img> wrapper.
 * - na_time_ago(): "X घण्टा अघि" formatted relative time in Devanagari.
 * - na_to_devanagari(): integer → Devanagari digits (for view counts etc).
 * - na_ad_slot(): unified ad placeholder block.
 * - na_excerpt(): trimmed excerpt with Devanagari ellipsis.
 * - na_get_*(): post lookup helpers (most-read, candidate news, party rosters).
 */
if (! defined('ABSPATH')) exit;

/**
 * Normalize an ACF image field into a predictable shape regardless of the
 * field's "Return Format" (Image Array | Image URL | Image ID).
 *
 * Returns null when the input is empty.
 *
 * @param mixed $img ACF image field value.
 * @return array{id:int,url:string,width:int,height:int,alt:string}|null
 */
function na_image_data($img) {
    if (empty($img)) return null;

    $id = 0;
    if (is_array($img)) {
        $id = isset($img['ID']) ? (int) $img['ID'] : (isset($img['id']) ? (int) $img['id'] : 0);
    } elseif (is_numeric($img)) {
        $id = (int) $img;
    } elseif (is_string($img) && strpos($img, 'http') === 0) {
        // URL-only return format — no ID, just emit the URL.
        return array('id' => 0, 'url' => $img, 'width' => 0, 'height' => 0, 'alt' => '');
    }

    if (! $id) {
        if (is_array($img) && ! empty($img['url'])) {
            return array(
                'id'     => 0,
                'url'    => (string) $img['url'],
                'width'  => isset($img['width'])  ? (int) $img['width']  : 0,
                'height' => isset($img['height']) ? (int) $img['height'] : 0,
                'alt'    => isset($img['alt'])    ? (string) $img['alt'] : '',
            );
        }
        return null;
    }

    $url = wp_get_attachment_image_url($id, 'full');
    if (! $url) return null;
    $meta = wp_get_attachment_metadata($id) ?: array();
    return array(
        'id'     => $id,
        'url'    => $url,
        'width'  => isset($meta['width'])  ? (int) $meta['width']  : 0,
        'height' => isset($meta['height']) ? (int) $meta['height'] : 0,
        'alt'    => (string) get_post_meta($id, '_wp_attachment_image_alt', true),
    );
}

/**
 * Render a responsive <img> for a post's featured image. Always carries an
 * `aspect-ratio` inline style so layout never shifts as images load. Falls
 * back to an empty `<div class="thumb ph">` placeholder when there's no
 * thumbnail so card layouts still keep their shape.
 *
 * @param int|null $post_id Post to pull thumbnail from. Null = current loop post.
 * @param string   $sizes   `sizes` attribute. Default "100vw".
 * @param string   $label   Aria/data-label fallback shown on the placeholder.
 * @param string   $size    WP image size. Default "large".
 * @param string   $loading "lazy" | "eager". Default "lazy".
 * @param array    $args    Extra HTML attributes: ['class' => '...', 'alt' => '...',
 *                          'aspect' => '16/9'].
 * @return string HTML markup.
 */
function na_responsive_img($post_id = null, $sizes = '100vw', $label = '', $size = 'large', $loading = 'lazy', $args = array()) {
    $post_id  = $post_id ?: get_the_ID();
    $thumb_id = get_post_thumbnail_id($post_id);
    $aspect   = isset($args['aspect']) ? $args['aspect'] : '16/9';
    unset($args['aspect']);

    $aspect_style = 'aspect-ratio:' . esc_attr($aspect) . ';width:100%;height:auto;object-fit:cover;display:block;';

    if (! $thumb_id) {
        $placeholder = na_image_data(function_exists('na_option') ? na_option('ts_placeholder_image') : null);
        if ($placeholder && $placeholder['id']) {
            $thumb_id = $placeholder['id'];
        } else {
            $label_attr = $label ? ' data-label="' . esc_attr($label) . '"' : '';
            $class      = isset($args['class']) ? ' ' . esc_attr($args['class']) : '';
            return '<div class="thumb ph' . $class . '" style="aspect-ratio:' . esc_attr($aspect) . ';"' . $label_attr . '></div>';
        }
    }

    $existing_style = isset($args['style']) ? rtrim((string) $args['style'], ';') . ';' : '';
    $args = array_merge(array(
        'loading' => $loading,
        'sizes'   => $sizes,
        'alt'     => get_the_title($post_id),
    ), $args);
    $args['style'] = $existing_style . $aspect_style;

    return wp_get_attachment_image($thumb_id, $size, false, $args);
}

/**
 * Theme-wide guarantee: every <img> rendered through wp_get_attachment_image()
 * carries an `aspect-ratio` style derived from its width/height attrs.
 *
 * Catches editor-inserted core/image blocks, post thumbnails on
 * single/archive templates, and any custom code paths that don't go
 * through na_thumb() / na_responsive_img() — keeps the layout from
 * shifting as images decode.
 *
 * @param array $attr Existing attribute array.
 * @param WP_Post $attachment
 * @param string|array $size
 * @return array
 */
function na_force_aspect_ratio_on_img($attr, $attachment, $size) {
    $existing_style = isset($attr['style']) ? rtrim((string) $attr['style'], ';') . ';' : '';

    // Don't double-apply.
    if (stripos($existing_style, 'aspect-ratio') !== false) {
        return $attr;
    }

    // Respect explicit width:100% / height:100% (used by na_thumb so the
    // <img> fills its .ph wrapper). Without this guard we'd append
    // height:auto and the placeholder would show its natural size.
    $skip_height = (stripos($existing_style, 'height:100%') !== false || stripos($existing_style, 'height: 100%') !== false);

    $w = isset($attr['width'])  ? (int) $attr['width']  : 0;
    $h = isset($attr['height']) ? (int) $attr['height'] : 0;

    if ($w > 0 && $h > 0) {
        $attr['style'] = $existing_style . 'aspect-ratio:' . $w . '/' . $h . ';' . ($skip_height ? '' : 'height:auto;');
    } else {
        // Sensible default when dimensions are unknown.
        $defaults = 'aspect-ratio:16/9;width:100%;object-fit:cover;display:block;';
        if (! $skip_height) $defaults .= 'height:auto;';
        $attr['style'] = $existing_style . $defaults;
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'na_force_aspect_ratio_on_img', 10, 3);

/**
 * Aspect-ratio'd thumbnail wrapper used by every block render.
 *
 * Always emits a `<div class="thumb ph">` with `aspect-ratio` set inline
 * — even when the post has no featured image, so the card layout never
 * collapses. When a thumbnail exists, the inner `<img>` fills the wrapper
 * with `object-fit:cover` so the aspect ratio is honoured visually.
 *
 * @param int|WP_Post $post     Post (or ID) whose thumbnail to render.
 * @param string      $aspect   CSS aspect-ratio value (e.g. "16/9", "4/3", "1/1").
 * @param string      $size     WP image size. Default "medium_large".
 * @param string      $sizes    `sizes` attribute for srcset. Default "100vw".
 * @param string      $loading  "lazy" | "eager".
 * @param array       $opts     Optional: cat_tag (string), label (string), extra_class (string), inner_html (string).
 */
function na_thumb($post, $aspect = '16/9', $size = 'medium_large', $sizes = '100vw', $loading = 'lazy', $opts = array()) {
    $post = $post instanceof WP_Post ? $post : get_post($post);
    $thumb_id = $post ? get_post_thumbnail_id($post) : 0;

    if (! $thumb_id) {
        $placeholder = na_image_data(function_exists('na_option') ? na_option('ts_placeholder_image') : null);
        if ($placeholder && $placeholder['id']) {
            $thumb_id = $placeholder['id'];
        }
    }

    $extra_class = isset($opts['extra_class']) ? ' ' . $opts['extra_class'] : '';
    $label       = $opts['label'] ?? '';
    $cat_tag     = $opts['cat_tag'] ?? '';
    $inner_html  = $opts['inner_html'] ?? '';

    $style    = 'aspect-ratio:' . esc_attr($aspect) . ';';
    $has_img  = (bool) $thumb_id;
    $wrap_cls = 'thumb ph' . esc_attr($extra_class) . ($has_img ? ' has-img' : '');

    $out  = '<div class="' . $wrap_cls . '" style="' . $style . '"' . ($label ? ' data-label="' . esc_attr($label) . '"' : '') . '>';

    if ($has_img) {
        $img_style = 'aspect-ratio:' . esc_attr($aspect) . ';width:100%;height:100%;object-fit:cover;display:block;';
        $out .= wp_get_attachment_image($thumb_id, $size, false, array(
            'loading' => $loading,
            'sizes'   => $sizes,
            'alt'     => $post ? get_the_title($post) : '',
            'style'   => $img_style,
        ));
    }
    if ($inner_html) $out .= $inner_html;
    if ($cat_tag)    $out .= '<div class="cat-tag">' . esc_html($cat_tag) . '</div>';

    $out .= '</div>';
    return $out;
}

/**
 * Map ASCII digits to Devanagari numerals — used for view counts, dates,
 * "X minutes ago" labels.
 *
 * @param int|string $n
 * @return string
 */
function na_to_devanagari($n) {
    $map = array(
        '0' => '०', '1' => '१', '2' => '२', '3' => '३', '4' => '४',
        '5' => '५', '6' => '६', '7' => '७', '8' => '८', '9' => '९',
    );
    return strtr((string) $n, $map);
}

/**
 * "X घण्टा अघि" relative time. Defaults to the current loop post.
 *
 * @param int|null $post_id
 * @return string
 */
function na_time_ago($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    if (! $post_id) return '';

    $delta = max(0, current_time('timestamp') - get_post_time('U', true, $post_id));

    if ($delta < HOUR_IN_SECONDS) {
        $mins = max(1, (int) ($delta / MINUTE_IN_SECONDS));
        return na_to_devanagari($mins) . ' मिनेट अघि';
    }
    if ($delta < DAY_IN_SECONDS) {
        $hrs = (int) ($delta / HOUR_IN_SECONDS);
        return na_to_devanagari($hrs) . ' घण्टा अघि';
    }
    if ($delta < WEEK_IN_SECONDS) {
        $days = (int) ($delta / DAY_IN_SECONDS);
        return na_to_devanagari($days) . ' दिन अघि';
    }
    return na_to_devanagari(get_the_date('j', $post_id)) . ' ' . get_the_date('F', $post_id);
}

/**
 * Render an ad placeholder. Wired into the markup so editors can swap in
 * an actual ad-slot block later without touching templates.
 *
 * @param string $slot        Slot key ("header_banner", "in_article", etc).
 * @param string $extra_class Extra wrapper class.
 */
function na_ad_slot($slot, $extra_class = '') {
    $size_map = array(
        'header_banner'  => array('label' => 'Header banner · 970×90',   'aspect' => '970/90'),
        'in_article'     => array('label' => 'In-article · 728×90',     'aspect' => '728/90'),
        'sidebar_top'    => array('label' => 'Sidebar · 300×250',       'aspect' => '300/250'),
        'sidebar_sticky' => array('label' => 'Sticky sidebar · 300×600', 'aspect' => '300/600'),
        'footer_banner'  => array('label' => 'Footer banner · 970×90',  'aspect' => '970/90'),
    );
    $spec = $size_map[$slot] ?? array('label' => 'Advertisement', 'aspect' => '4/1');
    ?>
    <div class="ad-slot <?php echo esc_attr($extra_class); ?>" data-slot="<?php echo esc_attr($slot); ?>">
        <div class="ph" style="aspect-ratio: <?php echo esc_attr($spec['aspect']); ?>;" data-label="<?php echo esc_attr($spec['label']); ?>"></div>
    </div>
    <?php
}

/**
 * Trimmed excerpt with a Devanagari-friendly ellipsis. Strips shortcodes
 * and tags first.
 *
 * @param int|null $post_id
 * @param int      $words
 * @return string
 */
function na_excerpt($post_id = null, $words = 28) {
    $post_id = $post_id ?: get_the_ID();
    $raw     = get_post_field('post_excerpt', $post_id);
    if (! $raw) {
        $raw = wp_strip_all_tags(strip_shortcodes(get_post_field('post_content', $post_id)));
    }
    return wp_trim_words($raw, $words, ' …');
}

/**
 * Latest "most-read" posts. Falls back to most recent if there's no
 * view-count meta yet. Used by the home-sidebar.
 *
 * @param int $limit
 * @return WP_Query
 */
function na_get_most_read($limit = 5) {
    return new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        'meta_key'       => 'na_view_count',
        'orderby'        => array('meta_value_num' => 'DESC', 'date' => 'DESC'),
        'no_found_rows'  => true,
    ));
}

/**
 * News related to a candidate by title-match. Used on single-candidate.php.
 */
function na_get_candidate_news($name, $limit = 6) {
    return new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        's'              => $name,
        'no_found_rows'  => true,
    ));
}

/**
 * Candidates connected to a party via the candidate_current_party ACF field.
 */
function na_get_party_candidates($party_id, $limit = 5) {
    return new WP_Query(array(
        'post_type'      => 'candidate',
        'posts_per_page' => $limit,
        'meta_query'     => array(
            array(
                'key'   => 'candidate_current_party',
                'value' => (int) $party_id,
            ),
        ),
        'no_found_rows'  => true,
    ));
}

/**
 * Stored location ACF field (used for candidates). Returns an array shape
 * that the badge helper understands.
 *
 * @return array{province?:string,district?:string,municipality?:string}
 */
function na_get_post_location($post_id) {
    $loc = function_exists('get_field') ? get_field('location', $post_id) : array();
    return is_array($loc) ? $loc : array();
}

function na_render_location_badge($post_id) {
    $loc = na_get_post_location($post_id);
    if (! $loc) return '';
    $bits = array_filter(array($loc['municipality'] ?? '', $loc['district'] ?? '', $loc['province'] ?? ''));
    return esc_html(implode(', ', $bits));
}

/**
 * Increment a post's view count. Front controllers can call this on
 * single template loads; we store it as plain meta for portability.
 */
function na_increment_view_count($post_id) {
    $current = (int) get_post_meta($post_id, 'na_view_count', true);
    update_post_meta($post_id, 'na_view_count', $current + 1);
}

/**
 * Resolve a primary category for a post (first non-Uncategorized term).
 * Returns ['slug','name','np'] using na_get_categories() for the Devanagari name.
 *
 * @return array{slug:string,name:string,np:string}
 */
function na_primary_category($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $terms = get_the_category($post_id);
    foreach ($terms as $t) {
        if ($t->slug === 'uncategorized') continue;
        $np = $t->name;
        foreach (na_get_categories() as $row) {
            if ($row['slug'] === $t->slug) { $np = $row['np']; break; }
        }
        return array(
            'id'   => (int) $t->term_id,
            'slug' => $t->slug,
            'name' => $t->name,
            'np'   => $np,
            'link' => get_term_link($t),
        );
    }
    return array('id' => 0, 'slug' => '', 'name' => '', 'np' => '', 'link' => '');
}
