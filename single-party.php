<?php
/**
 * Single Party Template
 */
get_header();

if (have_posts()): while (have_posts()): the_post();

$party_id = get_the_ID();
$short_name = get_field('party_short_name');
$established = get_field('party_established');
$chairman_id = get_field('party_chairman');
$chairman = $chairman_id ? get_post($chairman_id) : null;
$ideology = get_field('party_ideology');
$party_color = get_field('party_color') ?: '#E21F26';
$symbol_url = get_field('party_symbol');
$website = get_field('party_website');
?>

<main class="single-party">
    <?php na_ad_slot('header_banner', 'ad-slot--center'); ?>

    <div class="container">
        <div class="single-party__layout">
            <article class="single-party__main">

                <!-- Party Header -->
                <div class="party-header" style="--party-color: <?php echo esc_attr($party_color); ?>">
                    <div class="party-header__logo">
                        <?php if (has_post_thumbnail()): ?>
                            <?php echo na_responsive_img($party_id, '(max-width:768px) 30vw, 200px', '', 'medium', 'eager'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="party-header__info">
                        <h1 class="party-header__name">
                            <?php the_title(); ?>
                            <?php if ($short_name): ?>
                                <span class="party-header__short">(<?php echo esc_html($short_name); ?>)</span>
                            <?php endif; ?>
                        </h1>
                        <div class="party-header__meta">
                            <?php if ($established): ?>
                                <span><strong>स्थापना:</strong> <?php echo esc_html($established); ?></span>
                            <?php endif; ?>
                            <?php if ($ideology): ?>
                                <span><strong>विचारधारा:</strong> <?php echo esc_html($ideology); ?></span>
                            <?php endif; ?>
                            <?php if ($chairman): ?>
                                <span><strong>अध्यक्ष:</strong>
                                    <a href="<?php echo get_permalink($chairman); ?>"><?php echo esc_html($chairman->post_title); ?></a>
                                </span>
                            <?php endif; ?>
                            <?php if ($website): ?>
                                <span><a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener">आधिकारिक वेबसाइट →</a></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($symbol_url): ?>
                        <div class="party-header__symbol">
                            <?php echo na_responsive_img_url($symbol_url, get_the_title() . ' — चुनाव चिन्ह'); ?>
                            <span>चुनाव चिन्ह</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- About -->
                <?php if (get_the_content()): ?>
                    <div class="party-section">
                        <h2 class="party-section__title">परिचय</h2>
                        <div class="party-section__body general-content-box styled-list">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php na_ad_slot('in_article', 'ad-slot--in-content'); ?>

                <!-- Party Members / Key Leaders -->
                <div class="party-section">
                    <h2 class="party-section__title">प्रमुख नेताहरू</h2>
                    <?php
                    $members = na_get_party_candidates($party_id, 12);
                    if ($members->have_posts()):
                    ?>
                        <div class="party-members-grid">
                            <?php while ($members->have_posts()): $members->the_post(); ?>
                                <a href="<?php the_permalink(); ?>" class="party-member-card">
                                    <div class="party-member-card__image">
                                        <?php if (has_post_thumbnail()): ?>
                                            <?php echo na_responsive_img(null, '(max-width:768px) 50vw, 200px', '', 'medium'); ?>
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="party-member-card__name"><?php the_title(); ?></h4>
                                    <?php
                                    $position = get_field('candidate_career');
                                    if ($position && !empty($position[0]['position'])):
                                    ?>
                                        <span class="party-member-card__role"><?php echo esc_html($position[0]['position']); ?></span>
                                    <?php endif; ?>
                                </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    <?php else: ?>
                        <p>कुनै सदस्य सूचीबद्ध छैनन्।</p>
                    <?php endif; ?>
                </div>

                <!-- Related News -->
                <div class="party-section">
                    <h2 class="party-section__title">सम्बन्धित समाचार</h2>
                    <?php
                    $party_news = na_get_party_news(get_the_title(), 8);
                    if ($party_news->have_posts()):
                    ?>
                        <div class="party-news-grid">
                            <?php while ($party_news->have_posts()): $party_news->the_post(); ?>
                                <a href="<?php the_permalink(); ?>" class="party-news-item">
                                    <div class="party-news-item__thumb">
                                        <?php if (has_post_thumbnail()): ?>
                                            <?php echo na_responsive_img(null, '(max-width:768px) 50vw, 300px', '', 'medium'); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="party-news-item__content">
                                        <h4><?php the_title(); ?></h4>
                                        <span><?php echo na_time_ago(); ?></span>
                                    </div>
                                </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    <?php else: ?>
                        <p>कुनै सम्बन्धित समाचार छैन।</p>
                    <?php endif; ?>
                </div>

            </article>

            <aside class="single-party__sidebar">
                <?php na_ad_slot('sidebar_top'); ?>

                <!-- Other parties -->
                <div class="sidebar-widget">
                    <h3 class="sidebar-widget__title">अन्य दलहरू</h3>
                    <?php
                    $other_parties = new WP_Query([
                        'post_type'      => 'party',
                        'posts_per_page' => 6,
                        'post__not_in'   => [$party_id],
                    ]);
                    if ($other_parties->have_posts()):
                        while ($other_parties->have_posts()): $other_parties->the_post();
                    ?>
                        <a href="<?php the_permalink(); ?>" class="sidebar-widget__party-item">
                            <?php if (has_post_thumbnail()): ?>
                                <?php echo na_responsive_img(null, '40px', '', 'thumbnail'); ?>
                            <?php endif; ?>
                            <span><?php the_title(); ?></span>
                        </a>
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>

                <?php na_ad_slot('sidebar_sticky'); ?>
            </aside>
        </div>
    </div>

    <?php na_ad_slot('footer_banner', 'ad-slot--center'); ?>
</main>

<?php endwhile; endif;
get_footer();
?>
