<?php
/**
 * Block: Opinion / Columns.
 *
 * ACF fields (group_block_opinion):
 *   - title_np / title_en / more_link
 *   - columns (relationship, max 4)  — picks from posts in the "opinion" category.
 */
if (! defined('ABSPATH')) exit;

$title_np  = na_field('title_np',  null, 'विचार र विश्लेषण');
$title_en  = na_field('title_en',  null, 'Opinion · Columns');
$more_link = na_field('more_link', null, '');
$posts     = na_field('columns',   null, array());

if (! $posts) {
    $term = get_term_by('slug', 'opinion', 'category');
    $posts = $term ? get_posts(array('numberposts' => 4, 'cat' => $term->term_id)) : get_posts(array('numberposts' => 4));
}
?>
<section class="section opinion-block">
    <div class="container-wide">
        <div class="section-head">
            <h2><?php echo esc_html($title_np); ?> <span class="en-sub"><?php echo esc_html($title_en); ?></span></h2>
            <?php if ($more_link): ?><a href="<?php echo esc_url($more_link); ?>" class="more">सबै →</a><?php endif; ?>
        </div>
        <div class="opinion-grid">
            <?php foreach ((array) $posts as $p): if (! $p instanceof WP_Post) $p = get_post($p); if (! $p) continue; ?>
                <a href="<?php echo esc_url(get_permalink($p)); ?>" class="opinion-card">
                    <div class="opinion-card__author">
                        <?php echo get_avatar($p->post_author, 48); ?>
                        <div>
                            <div class="opinion-card__name"><?php echo esc_html(get_the_author_meta('display_name', $p->post_author)); ?></div>
                            <div class="opinion-card__role"><?php echo esc_html(get_the_author_meta('description', $p->post_author) ?: 'स्तम्भकार'); ?></div>
                        </div>
                    </div>
                    <h3 class="title"><?php echo esc_html(get_the_title($p)); ?></h3>
                    <p class="excerpt"><?php echo esc_html(na_excerpt($p->ID, 24)); ?></p>
                    <div class="meta"><?php echo esc_html(na_time_ago($p->ID)); ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
