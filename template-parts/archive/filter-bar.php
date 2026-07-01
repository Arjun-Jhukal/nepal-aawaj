<?php
/**
 * Filter chips (child terms of the current archive term) + sort select.
 * If the archive isn't a taxonomy archive, falls back to recent post_tag terms.
 * Sort options are static labels — frontend JS does the heavy lifting.
 */
if (! defined('ABSPATH')) exit;

global $wp_query;

$term     = is_category() || is_tag() || is_tax() ? get_queried_object() : null;
$chips    = array();
$count    = (int) ($wp_query->found_posts ?? 0);

if ($term) {
    $children = get_terms(array(
        'taxonomy'   => $term->taxonomy,
        'parent'     => $term->term_id,
        'hide_empty' => true,
    ));
    if (! is_wp_error($children) && $children) {
        foreach ($children as $child) {
            $chips[] = array('slug' => $child->slug, 'label' => $child->name);
        }
    }
}

if (! $chips) {
    $tags = get_terms(array('taxonomy' => 'post_tag', 'number' => 6, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true));
    if (! is_wp_error($tags)) {
        foreach ($tags as $t) {
            $chips[] = array('slug' => $t->slug, 'label' => $t->name);
        }
    }
}
?>
<div class="filter-bar">
    <div class="left">
        <span class="kicker kicker-ink" style="margin-right:6px;">फिल्टर</span>
        <button class="chip active" data-filter="all">सबै</button>
        <?php foreach ($chips as $c): ?>
            <button class="chip" data-filter="<?php echo esc_attr($c['slug']); ?>"><?php echo esc_html($c['label']); ?></button>
        <?php endforeach; ?>
    </div>
    <div class="left">
        <?php if ($count): ?>
            <span class="count en"><?php echo esc_html(na_to_devanagari(number_format($count))); ?> समाचार</span>
        <?php endif; ?>
        <select class="sort-select">
            <option>नयाँ पहिले</option>
            <option>पुरानो पहिले</option>
            <option>सबैभन्दा पढिएको</option>
            <option>सम्पादक छनोट</option>
        </select>
    </div>
</div>
