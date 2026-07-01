<?php
/**
 * Block: Hero Grid
 *
 * ACF fields (group_block_hero):
 *   - lead_post   (post_object)        — the big left-hand card.
 *   - side_posts  (relationship max 4) — the 4 stacked items on the right.
 *
 * Falls back to the most-recent post (lead) + next 4 posts (side) when
 * the editor hasn't picked anything, so the block never renders empty.
 */
if (! defined('ABSPATH')) exit;

$lead = na_field('lead_post');
if (! $lead) {
    $latest = get_posts(array('numberposts' => 1, 'post_status' => 'publish'));
    $lead   = $latest ? $latest[0] : null;
}

$side = na_field('side_posts', null, array());
if (! $side) {
    $exclude = $lead ? array($lead->ID) : array();
    $side    = get_posts(array('numberposts' => 4, 'exclude' => $exclude, 'post_status' => 'publish'));
}
$side = array_slice((array) $side, 0, 4);
?>
<section class="container-wide">
    <div class="hero-grid">
        <?php if ($lead): $cat = na_primary_category($lead->ID); ?>
            <a href="<?php echo esc_url(get_permalink($lead)); ?>" class="card hero-lead">
                <?php echo na_thumb($lead, '16/10', 'large', '(max-width:900px) 100vw, 60vw', 'eager', array(
                    'extra_class' => 'ph-red',
                    'label'       => $cat['np'] ?: 'Lead',
                    'cat_tag'     => $cat['np'],
                )); ?>
                <div class="body">
                    <div class="kicker">मुख्य समाचार · LEAD STORY</div>
                    <h2 class="title" style="margin-top:10px;"><?php echo esc_html(get_the_title($lead)); ?></h2>
                    <p class="excerpt"><?php echo esc_html(na_excerpt($lead->ID, 40)); ?></p>
                    <div class="meta">
                        <span class="author"><?php echo esc_html(get_the_author_meta('display_name', $lead->post_author)); ?></span>
                        <span class="dot"></span>
                        <span><?php echo esc_html(na_time_ago($lead->ID)); ?></span>
                        <span class="dot"></span>
                        <span><?php echo esc_html(na_to_devanagari(max(1, (int) ceil(str_word_count(strip_tags($lead->post_content)) / 200)))); ?> मिनेट पढाइ</span>
                        <?php $views = (int) get_post_meta($lead->ID, 'na_view_count', true); if ($views): ?>
                            <span class="dot"></span>
                            <span style="color: var(--red); font-weight: 600;">●  <?php echo esc_html(na_to_devanagari(number_format($views))); ?> हेरिएको</span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endif; ?>

        <div class="hero-side">
            <?php foreach ($side as $p): $cat = na_primary_category($p->ID); ?>
                <a href="<?php echo esc_url(get_permalink($p)); ?>" class="item">
                    <?php echo na_thumb($p, '4/3', 'medium', '(max-width:900px) 100vw, 25vw', 'lazy', array(
                        'label' => $cat['np'] ?: '',
                    )); ?>
                    <div>
                        <div class="kicker"><?php echo esc_html($cat['np']); ?></div>
                        <h3 class="title" style="margin-top:6px;"><?php echo esc_html(get_the_title($p)); ?></h3>
                        <div class="meta"><?php echo esc_html(na_time_ago($p->ID)); ?> · <?php echo esc_html(get_the_author_meta('display_name', $p->post_author)); ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
