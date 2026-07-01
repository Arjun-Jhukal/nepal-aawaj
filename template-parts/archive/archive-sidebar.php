<?php
/**
 * Archive sidebar — dynamic data only.
 *
 * Editor's Pick : sticky posts (falls back to recent in the same term).
 * Browse Topics : real categories from na_get_categories().
 * Most Read     : na_get_most_read() (view-count meta, recency fallback).
 * Ad slot       : unified na_ad_slot helper.
 *
 * Panels self-suppress when there's no content to show, so empty sections
 * don't render orphaned headings.
 */
if (! defined('ABSPATH')) exit;

/* ---------- Editor's Pick: stickies first, otherwise latest in this term. */
$queried   = get_queried_object();
$term_tax  = null;
if ($queried && isset($queried->taxonomy)) {
    $term_tax = array(
        'taxonomy' => $queried->taxonomy,
        'field'    => 'term_id',
        'terms'    => $queried->term_id,
    );
}

$sticky_ids   = get_option('sticky_posts');
$editors_args = array(
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'no_found_rows'       => true,
    'ignore_sticky_posts' => 1,
);
if (! empty($sticky_ids)) {
    $editors_args['post__in'] = $sticky_ids;
    $editors_args['orderby']  = 'post__in';
} else {
    $editors_args['orderby'] = 'date';
    $editors_args['order']   = 'DESC';
    if ($term_tax) {
        $editors_args['tax_query'] = array($term_tax);
    }
}
$editors_q = new WP_Query($editors_args);

/* ---------- Most read */
$most_read = na_get_most_read(5);

/* ---------- Topic jump (replaces the old static province list) */
$topics = function_exists('na_get_categories') ? na_get_categories() : array();
?>
<aside>
    <?php if ($editors_q->have_posts()): ?>
    <div class="panel">
        <div class="panel-head">
            <span>सम्पादक छनोट</span>
            <span class="en-sub">Editor's Pick</span>
        </div>
        <div class="panel-body">
            <?php
            $total = $editors_q->post_count;
            $i = 0;
            while ($editors_q->have_posts()): $editors_q->the_post();
                $i++;
                $border = ($i === $total) ? '' : 'border-bottom: 1px solid var(--gray-2);';
                $cat    = na_primary_category();
            ?>
            <a href="<?php the_permalink(); ?>" class="card-row card" style="<?php echo esc_attr($border); ?> padding: 14px 0;">
                <?php echo na_thumb(get_post(), '4/3', 'thumbnail', '88px', 'lazy', array('label' => $cat['np'] ?: $cat['name'])); ?>
                <div>
                    <h4 class="title" style="font-family: var(--f-display-dn); font-size: 14px;"><?php the_title(); ?></h4>
                    <div class="meta" style="font-family: var(--f-ui); font-size: 11px; color: var(--gray-6); margin-top: 4px;"><?php echo esc_html(na_time_ago()); ?></div>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (! empty($topics)): ?>
    <div class="panel">
        <div class="panel-head">
            <span>विषयहरू</span>
            <span class="en-sub">Browse Topics</span>
        </div>
        <div class="panel-body" style="padding: 12px 16px 16px;">
            <ul class="province-jump">
                <?php
                $last_idx = count($topics) - 1;
                foreach ($topics as $idx => $t):
                    $is_full = ($last_idx % 2 === 0) && ($idx === $last_idx);
                    $cls     = $is_full ? ' class="full"' : '';
                ?>
                <li<?php echo $cls; ?>><a href="<?php echo esc_url(na_cat_link($t['slug'])); ?>"><?php echo esc_html($t['np']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($most_read->have_posts()): ?>
    <div class="panel">
        <div class="panel-head">
            <span>सर्वाधिक पढिएको</span>
            <span class="en-sub">Most Read</span>
        </div>
        <div class="panel-body">
            <div class="numlist">
                <?php while ($most_read->have_posts()): $most_read->the_post();
                    $views = (int) get_post_meta(get_the_ID(), 'na_view_count', true);
                ?>
                <a href="<?php the_permalink(); ?>" class="item">
                    <div>
                        <div class="title"><?php the_title(); ?></div>
                        <div class="meta">
                            <?php if ($views > 0): ?>
                                <?php echo esc_html(na_to_devanagari(number_format_i18n($views))); ?> हेरिएको
                            <?php else: ?>
                                <?php echo esc_html(na_time_ago()); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="panel" style="background: transparent; border-style: dashed;">
        <?php na_ad_slot('sidebar_top'); ?>
    </div>
</aside>
