<?php
/**
 * Block: Aside / Featured.
 *
 * ACF fields (group_block_aside_featured):
 *   - show_most_read (true_false)  default on
 *   - show_ad         (true_false)
 *   - show_trending   (true_false)
 *   - show_photo      (true_false)
 *   - photo_image     (image)
 *   - photo_caption   (textarea)
 *   - photo_credit    (string)
 *
 * Mirrors the existing home-sidebar.php partial but as a full-width block,
 * so editors can drop it under any section (not just inside `.two-col`).
 */
if (! defined('ABSPATH')) exit;

$show_most  = (bool) na_field('show_most_read', null, true);
$show_ad    = (bool) na_field('show_ad',        null, true);
$show_trend = (bool) na_field('show_trending',  null, true);
$show_photo = (bool) na_field('show_photo',     null, true);

$photo    = na_field('photo_image',   null, null);
$caption  = na_field('photo_caption', null, '"जेठको तातोमा भक्तपुरको दरबार क्षेत्रमा छाताको सहारा लिँदै पर्यटक।"');
$credit   = na_field('photo_credit',  null, 'सुजन श्रेष्ठ');
?>
<section class="section aside-featured-block">
    <div class="container-wide">
        <aside class="aside-featured-grid">
            <?php if ($show_most): $most = na_get_most_read(5); ?>
                <div class="panel">
                    <div class="panel-head"><span>सर्वाधिक पढिएको</span><span class="en-sub">Most Read</span></div>
                    <div class="panel-body">
                        <div class="numlist">
                            <?php while ($most->have_posts()): $most->the_post(); $cat = na_primary_category(); ?>
                                <a href="<?php the_permalink(); ?>" class="item">
                                    <div>
                                        <div class="title"><?php the_title(); ?></div>
                                        <div class="meta"><?php echo esc_html(na_to_devanagari(number_format((int) get_post_meta(get_the_ID(), 'na_view_count', true)))); ?> हेरिएको · <?php echo esc_html($cat['np']); ?></div>
                                    </div>
                                </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($show_ad): na_ad_slot('sidebar_top'); endif; ?>

            <?php if ($show_trend): ?>
                <div class="panel">
                    <div class="panel-head"><span>ट्रेन्डिङ विषय</span><span class="en-sub">Trending</span></div>
                    <div class="panel-body" style="padding-top:16px;">
                        <div class="tags">
                            <?php
                            $terms = get_terms(array('taxonomy' => 'post_tag', 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hide_empty' => true));
                            if (is_wp_error($terms) || ! $terms) {
                                foreach (array('बजेट २०८३','विश्वकप क्रिकेट','डेङ्गु प्रकोप','निर्वाचन आयोग','SEE नतिजा','AI नीति','पर्यटन','शेयर बजार','जलवायु संकट','गठबन्धन') as $label) {
                                    echo '<a class="tag" href="#"># ' . esc_html($label) . '</a>';
                                }
                            } else {
                                foreach ($terms as $term) {
                                    echo '<a class="tag" href="' . esc_url(get_term_link($term)) . '"># ' . esc_html($term->name) . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($show_photo): ?>
                <div class="panel">
                    <div class="panel-head"><span>दिनको तस्बिर</span><span class="en-sub">Photo of the Day</span></div>
                    <?php if ($photo && is_array($photo)): ?>
                        <img src="<?php echo esc_url($photo['url']); ?>" alt="<?php echo esc_attr($photo['alt'] ?? ''); ?>" loading="lazy" style="aspect-ratio:4/3;width:100%;height:auto;object-fit:cover;display:block;" />
                    <?php else: ?>
                        <div class="ph" style="aspect-ratio:4/3;" data-label="Photo · Documentary"></div>
                    <?php endif; ?>
                    <div class="panel-body">
                        <p style="font-size:13px;color:var(--gray-7);margin-top:12px;font-style:italic;line-height:1.5;"><?php echo esc_html($caption); ?></p>
                        <div class="meta" style="font-family:var(--f-ui);font-size:11px;color:var(--gray-6);margin-top:8px;">तस्बिर: <?php echo esc_html($credit); ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</section>
