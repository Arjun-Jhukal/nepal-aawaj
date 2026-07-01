<?php
/**
 * Block: Trending Tags.
 *
 * ACF fields (group_block_trending):
 *   - title
 *   - mode  (radio: manual | auto)
 *   - tags  (repeater): { label, url }   — manual mode
 *   - count (number)                     — auto mode (most-used post_tags)
 */
if (! defined('ABSPATH')) exit;

$title = na_field('title', null, 'ट्रेन्डिङ विषय');
$mode  = na_field('mode',  null, 'auto');
$tags  = array();

if ($mode === 'manual') {
    $tags = na_field('tags', null, array());
} else {
    $count = (int) na_field('count', null, 10);
    $terms = get_terms(array(
        'taxonomy'   => 'post_tag',
        'orderby'    => 'count',
        'order'      => 'DESC',
        'hide_empty' => true,
        'number'     => $count ?: 10,
    ));
    if (! is_wp_error($terms)) {
        foreach ($terms as $term) {
            $tags[] = array('label' => $term->name, 'url' => get_term_link($term));
        }
    }
}

// Nothing to show? Don't render the empty panel.
if (! $tags) return;
?>
<div class="panel trending-block">
    <div class="panel-head">
        <span><?php echo esc_html($title); ?></span>
        <span class="en-sub">Trending</span>
    </div>
    <div class="panel-body" style="padding-top: 16px;">
        <div class="tags">
            <?php foreach ($tags as $tag): ?>
                <a class="tag" href="<?php echo esc_url($tag['url'] ?? '#'); ?>"># <?php echo esc_html($tag['label'] ?? ''); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
