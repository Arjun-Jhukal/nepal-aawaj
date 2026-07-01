<?php
/**
 * Featured/top story card for a category listing.
 * Picks the first post from the current archive query. Falls back to the
 * latest post in this archive if the main query has no posts.
 */
if (! defined('ABSPATH')) exit;

global $wp_query;

$feature = null;
if (have_posts() && ! empty($wp_query->posts)) {
    $feature = $wp_query->posts[0];
} else {
    $latest  = get_posts(array('numberposts' => 1, 'post_status' => 'publish'));
    $feature = $latest ? $latest[0] : null;
}

if (! $feature) return;

$cat   = na_primary_category($feature->ID);
$views = (int) get_post_meta($feature->ID, 'na_view_count', true);
?>
<a href="<?php echo esc_url(get_permalink($feature)); ?>" class="feature-story card">
    <?php echo na_thumb($feature, '16/9', 'large', '(max-width:900px) 100vw, 60vw', 'eager', array(
        'extra_class' => 'ph-red',
        'label'       => $cat['np'] ?: 'Lead',
        'cat_tag'     => 'विशेष',
    )); ?>
    <div>
        <div class="kicker">मुख्य कथा · TOP STORY</div>
        <h2 class="title"><?php echo esc_html(get_the_title($feature)); ?></h2>
        <p class="excerpt"><?php echo esc_html(na_excerpt($feature->ID, 40)); ?></p>
        <div class="meta">
            <span style="color:var(--ink); font-weight:700;"><?php echo esc_html(get_the_author_meta('display_name', $feature->post_author)); ?></span>
            <span class="dot"></span>
            <span><?php echo esc_html(na_time_ago($feature->ID)); ?></span>
            <?php if ($views): ?>
                <span class="dot"></span>
                <span style="color:var(--red); font-weight:600;">● <?php echo esc_html(na_to_devanagari(number_format($views))); ?> हेरिएको</span>
            <?php endif; ?>
        </div>
    </div>
</a>
