<?php
/**
 * Block: Featured Grid (three-column "categories-triple").
 *
 * ACF fields (group_block_featured_grid):
 *   - columns (repeater × 3):
 *       - category (taxonomy term)
 *       - posts    (relationship, max 4)
 */
if (! defined('ABSPATH')) exit;

$columns = na_field('columns', null, array());
if (! $columns) {
    foreach (array('sports', 'entertainment', 'tech') as $slug) {
        $term = get_term_by('slug', $slug, 'category');
        if (! $term) continue;
        $columns[] = array(
            'category' => $term,
            'posts'    => get_posts(array('numberposts' => 4, 'cat' => $term->term_id)),
        );
    }
}
?>
<section class="section">
    <div class="container-wide">
        <div class="triple-col">
            <?php foreach ((array) $columns as $col):
                $term  = is_object($col['category']) ? $col['category'] : ($col['category'] ? get_term((int) $col['category'], 'category') : null);
                $posts = isset($col['posts']) ? $col['posts'] : array();
                if (! $term) continue;
            ?>
            <div class="triple-col__item">
                <div class="section-head">
                    <h3><?php echo esc_html($term->name); ?></h3>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="more">सबै →</a>
                </div>
                <?php
                $first = true;
                foreach ((array) $posts as $p):
                    if (! $p instanceof WP_Post) $p = get_post($p);
                    if (! $p) continue;
                ?>
                    <?php if ($first): $first = false; ?>
                        <a href="<?php echo esc_url(get_permalink($p)); ?>" class="card col-feature">
                            <?php echo na_thumb($p, '16/9', 'medium_large', '(max-width:768px) 100vw, 25vw'); ?>
                            <div class="body">
                                <h3 class="title"><?php echo esc_html(get_the_title($p)); ?></h3>
                                <div class="meta"><?php echo esc_html(na_time_ago($p->ID)); ?></div>
                            </div>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo esc_url(get_permalink($p)); ?>" class="card-text">
                            <h4 class="title"><?php echo esc_html(get_the_title($p)); ?></h4>
                            <div class="meta"><?php echo esc_html(na_time_ago($p->ID)); ?></div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
