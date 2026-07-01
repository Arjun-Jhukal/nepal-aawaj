<?php
/**
 * Right sticky aside — related stories (same primary category), newsletter
 * mini, trending numlist (top viewed posts).
 */
if (! defined('ABSPATH')) exit;

$post_id = get_the_ID();
$cat     = na_primary_category($post_id);

$related = array();
if ($cat['id']) {
    $related = get_posts(array(
        'numberposts' => 4,
        'cat'         => $cat['id'],
        'exclude'     => array($post_id),
        'post_status' => 'publish',
    ));
}
if (! $related) {
    $related = get_posts(array('numberposts' => 4, 'exclude' => array($post_id), 'post_status' => 'publish'));
}

$trending = get_posts(array(
    'numberposts' => 3,
    'meta_key'    => 'na_view_count',
    'orderby'     => 'meta_value_num',
    'order'       => 'DESC',
    'exclude'     => array($post_id),
));
if (! $trending) {
    $trending = get_posts(array('numberposts' => 3, 'exclude' => array($post_id)));
}

$news_heading     = na_option('ts_newsletter_heading',     'न्युजलेटर');
$news_blurb       = na_option('ts_newsletter_blurb',       'दैनिक मुख्य समाचार आफ्नो इमेलमा पाउनुहोस्।');
$news_btn         = na_option('ts_newsletter_btn',         'सब्स्क्राइब');
$news_placeholder = na_option('ts_newsletter_placeholder', 'your@email.com');
?>
<aside class="article-side">
    <?php if ($related): ?>
    <div class="panel">
        <div class="panel-head"><span>यस्तै कथा</span><span class="en-sub">Related</span></div>
        <div class="panel-body">
            <?php $last = count($related) - 1; foreach ($related as $i => $p):
                $r_cat = na_primary_category($p->ID);
                $border = $i === $last ? '' : 'border-bottom: 1px solid var(--gray-2);';
            ?>
                <a href="<?php echo esc_url(get_permalink($p)); ?>" class="card-row card" style="<?php echo esc_attr($border); ?> padding: 14px 0;">
                    <?php echo na_thumb($p, '4/3', 'thumbnail', '60px', 'lazy', array('label' => $r_cat['np'])); ?>
                    <div>
                        <h4 class="title" style="font-family: var(--f-display-dn); font-size: 13px; line-height: 1.35;"><?php echo esc_html(get_the_title($p)); ?></h4>
                        <div class="meta" style="font-family: var(--f-ui); font-size: 10px; color: var(--gray-6); margin-top: 4px;"><?php echo esc_html(na_time_ago($p->ID)); ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="panel" style="background: var(--ink); color: #fff; border: none;">
        <div style="padding: 20px;">
            <div class="kicker" style="color: var(--red);">डेली डाइजेस्ट</div>
            <h4 style="font-family: var(--f-display-dn); font-size: 18px; color: #fff; margin: 8px 0;"><?php echo esc_html($news_heading); ?></h4>
            <p style="font-size: 13px; color: var(--gray-3); margin-bottom: 12px; line-height: 1.5;"><?php echo esc_html($news_blurb); ?></p>
            <form class="newsletter-mini">
                <input type="email" placeholder="<?php echo esc_attr($news_placeholder); ?>" required>
                <button type="submit"><?php echo esc_html($news_btn); ?></button>
            </form>
        </div>
    </div>

    <?php if ($trending): ?>
    <div class="panel">
        <div class="panel-head"><span>ट्रेन्डिङ</span><span class="en-sub">Trending</span></div>
        <div class="panel-body">
            <div class="numlist">
                <?php foreach ($trending as $p):
                    $views = (int) get_post_meta($p->ID, 'na_view_count', true);
                ?>
                    <a href="<?php echo esc_url(get_permalink($p)); ?>" class="item">
                        <div>
                            <div class="title" style="font-size: 13px;"><?php echo esc_html(get_the_title($p)); ?></div>
                            <?php if ($views): ?>
                                <div class="meta"><?php echo esc_html(na_to_devanagari(number_format($views))); ?> हेरिएको</div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</aside>
