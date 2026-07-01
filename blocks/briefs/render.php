<?php
/**
 * Block: News Briefs.
 *
 * ACF fields (group_block_briefs):
 *   - title
 *   - items (repeater): { headline, url, kicker, time_ago }
 *   - source_posts (relationship, optional)  — if filled, items are pulled
 *     live from these posts instead of the manual repeater.
 */
if (! defined('ABSPATH')) exit;

$title  = na_field('title', null, 'संक्षिप्त समाचार');
$source = na_field('source_posts', null, array());
$items  = na_field('items',  null, array());

if ($source) {
    $items = array();
    foreach ((array) $source as $p) {
        if (! $p instanceof WP_Post) $p = get_post($p);
        if (! $p) continue;
        $cat = na_primary_category($p->ID);
        $items[] = array(
            'headline' => get_the_title($p),
            'url'      => get_permalink($p),
            'kicker'   => $cat['np'],
            'time_ago' => na_time_ago($p->ID),
        );
    }
}
?>
<div class="panel briefs">
    <div class="panel-head"><span><?php echo esc_html($title); ?></span></div>
    <div class="panel-body">
        <ul class="briefs-list">
            <?php foreach ((array) $items as $item): ?>
                <li>
                    <a href="<?php echo esc_url($item['url'] ?? '#'); ?>">
                        <?php if (! empty($item['kicker'])): ?><span class="kicker"><?php echo esc_html($item['kicker']); ?></span><?php endif; ?>
                        <span class="headline"><?php echo esc_html($item['headline'] ?? ''); ?></span>
                        <?php if (! empty($item['time_ago'])): ?><span class="meta"><?php echo esc_html($item['time_ago']); ?></span><?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
