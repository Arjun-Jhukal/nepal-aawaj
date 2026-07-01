<?php
/**
 * Block: Latest News.
 *
 * ACF fields (group_block_latest_news):
 *   - title_np / title_en  — section heading.
 *   - more_link            — "see all" target.
 *   - featured_posts       — 4 posts for the 2x2 grid.
 *   - list_posts           — 4 posts for the row list.
 *   - show_sidebar (bool)  — render home-sidebar partial alongside.
 *
 * Falls back to the latest 8 published posts (split 4 + 4) when fields
 * are empty.
 */
if (! defined('ABSPATH')) exit;

$title_np  = na_field('title_np',     null, 'ताजा समाचार');
$title_en  = na_field('title_en',     null, 'Latest News');
$more_link = na_field('more_link',    null, '');
$featured  = na_field('featured_posts', null, array());
$list      = na_field('list_posts',     null, array());

if (! $featured) {
    $featured = get_posts(array('numberposts' => 4, 'post_status' => 'publish', 'offset' => 5));
}
if (! $list) {
    $exclude = array_map(static fn($p) => is_object($p) ? $p->ID : (int) $p, (array) $featured);
    $list    = get_posts(array('numberposts' => 4, 'post_status' => 'publish', 'exclude' => $exclude, 'offset' => 9));
}
?>
<section class="section">
    <div class="container-wide">
        <div>
            <div>
                <div class="section-head">
                    <h2><?php echo esc_html($title_np); ?> <span class="en-sub"><?php echo esc_html($title_en); ?></span></h2>
                    <?php if ($more_link): ?>
                        <a href="<?php echo esc_url($more_link); ?>" class="more">सबै हेर्नुहोस् →</a>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px; margin-bottom: 28px;">
                    <?php foreach ((array) $featured as $p): if (! $p instanceof WP_Post) $p = get_post($p); if (! $p) continue; $cat = na_primary_category($p->ID); ?>
                        <a href="<?php echo esc_url(get_permalink($p)); ?>" class="card card-medium">
                            <?php echo na_thumb($p, '16/10', 'medium_large', '(max-width:768px) 100vw, 30vw', 'lazy', array('cat_tag' => $cat['np'])); ?>
                            <div class="body">
                                <h3 class="title"><?php echo esc_html(get_the_title($p)); ?></h3>
                                <div class="meta"><span class="author"><?php echo esc_html(get_the_author_meta('display_name', $p->post_author)); ?></span><span class="dot"></span><span><?php echo esc_html(na_time_ago($p->ID)); ?></span></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div>
                    <?php foreach ((array) $list as $p): if (! $p instanceof WP_Post) $p = get_post($p); if (! $p) continue; $cat = na_primary_category($p->ID); ?>
                        <a href="<?php echo esc_url(get_permalink($p)); ?>" class="card-row card">
                            <?php echo na_thumb($p, '4/3', 'medium', '160px', 'lazy', array('cat_tag' => $cat['np'])); ?>
                            <div class="body">
                                <h3 class="title"><?php echo esc_html(get_the_title($p)); ?></h3>
                                <div class="meta"><span class="author"><?php echo esc_html(get_the_author_meta('display_name', $p->post_author)); ?></span><span class="dot"></span><span><?php echo esc_html(na_time_ago($p->ID)); ?></span></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</section>
