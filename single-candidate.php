<?php
/**
 * Single Candidate Template
 */
get_header();

if (have_posts()): while (have_posts()): the_post();

$candidate_id = get_the_ID();
$current_party_id = get_field('candidate_current_party');
$current_party = $current_party_id ? get_post($current_party_id) : null;
$career = get_field('candidate_career') ?: [];
$elections = get_field('candidate_elections') ?: [];
$social = get_field('candidate_social') ?: [];
$dob = get_field('candidate_dob');
$gender = get_field('candidate_gender');
$education = get_field('candidate_education');

// Location
$location = na_get_post_location($candidate_id);
?>

<main class="single-candidate">
    <?php na_ad_slot('header_banner', 'ad-slot--center'); ?>

    <div class="container">
        <div class="single-candidate__layout">
            <article class="single-candidate__main">

                <!-- Profile Header -->
                <div class="candidate-profile">
                    <div class="candidate-profile__image">
                        <?php if (has_post_thumbnail()): ?>
                            <?php echo na_responsive_img($candidate_id, '(max-width:768px) 100vw, 400px', '', 'medium_large', 'eager'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="candidate-profile__info">
                        <h1 class="candidate-profile__name"><?php the_title(); ?></h1>

                        <?php if ($current_party): ?>
                            <a href="<?php echo get_permalink($current_party); ?>" class="candidate-profile__party">
                                <?php if (has_post_thumbnail($current_party)): ?>
                                    <?php echo na_responsive_img($current_party, '40px', '', 'thumbnail', 'lazy', ['class' => 'candidate-profile__party-logo']); ?>
                                <?php endif; ?>
                                <span><?php echo esc_html($current_party->post_title); ?></span>
                            </a>
                        <?php endif; ?>

                        <div class="candidate-profile__details">
                            <?php if ($dob): ?>
                                <span><strong>जन्म मिति:</strong> <?php echo esc_html($dob); ?></span>
                            <?php endif; ?>
                            <?php if ($education): ?>
                                <span><strong>शिक्षा:</strong> <?php echo esc_html($education); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($location)): ?>
                                <span><strong>स्थान:</strong> <?php echo na_render_location_badge($candidate_id); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($social)): ?>
                            <div class="candidate-profile__social">
                                <?php if (!empty($social['facebook'])): ?>
                                    <a href="<?php echo esc_url($social['facebook']); ?>" target="_blank" rel="noopener">Facebook</a>
                                <?php endif; ?>
                                <?php if (!empty($social['twitter'])): ?>
                                    <a href="<?php echo esc_url($social['twitter']); ?>" target="_blank" rel="noopener">Twitter</a>
                                <?php endif; ?>
                                <?php if (!empty($social['website'])): ?>
                                    <a href="<?php echo esc_url($social['website']); ?>" target="_blank" rel="noopener">Website</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Bio -->
                <?php if (get_the_content()): ?>
                    <div class="candidate-section">
                        <h2 class="candidate-section__title">परिचय</h2>
                        <div class="candidate-section__body general-content-box styled-list">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Election History -->
                <?php if (!empty($elections)): ?>
                    <div class="candidate-section">
                        <h2 class="candidate-section__title">निर्वाचन इतिहास</h2>
                        <div class="candidate-elections">
                            <table class="candidate-table">
                                <thead>
                                    <tr>
                                        <th>निर्वाचन</th>
                                        <th>वर्ष</th>
                                        <th>दल</th>
                                        <th>क्षेत्र</th>
                                        <th>परिणाम</th>
                                        <th>मत</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($elections as $election):
                                        $e_party = $election['election_party'] ? get_post($election['election_party']) : null;
                                    ?>
                                        <tr class="result--<?php echo esc_attr($election['result']); ?>">
                                            <td><?php echo esc_html($election['election_name']); ?></td>
                                            <td><?php echo esc_html($election['election_year']); ?></td>
                                            <td>
                                                <?php if ($e_party): ?>
                                                    <a href="<?php echo get_permalink($e_party); ?>"><?php echo esc_html($e_party->post_title); ?></a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo esc_html($election['constituency']); ?></td>
                                            <td>
                                                <span class="result-badge result-badge--<?php echo esc_attr($election['result']); ?>">
                                                    <?php
                                                    $results_map = ['won' => 'विजयी', 'lost' => 'पराजित', 'pending' => 'प्रतिक्षामा'];
                                                    echo esc_html($results_map[$election['result']] ?? $election['result']);
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?php echo esc_html($election['votes'] ?: '-'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Career Timeline -->
                <?php if (!empty($career)): ?>
                    <div class="candidate-section">
                        <h2 class="candidate-section__title">राजनीतिक यात्रा</h2>
                        <div class="candidate-career">
                            <?php foreach ($career as $entry):
                                $c_party = $entry['party'] ? get_post($entry['party']) : null;
                            ?>
                                <div class="career-item">
                                    <span class="career-item__year"><?php echo esc_html($entry['year']); ?></span>
                                    <div class="career-item__content">
                                        <strong class="career-item__position"><?php echo esc_html($entry['position']); ?></strong>
                                        <?php if ($c_party): ?>
                                            <a href="<?php echo get_permalink($c_party); ?>" class="career-item__party">
                                                <?php echo esc_html($c_party->post_title); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($entry['event']): ?>
                                            <p class="career-item__event"><?php echo esc_html($entry['event']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php na_ad_slot('in_article', 'ad-slot--in-content'); ?>

                <!-- Related News about this candidate -->
                <div class="candidate-section">
                    <h2 class="candidate-section__title">सम्बन्धित समाचार</h2>
                    <?php
                    $news = na_get_candidate_news(get_the_title(), 6);
                    if ($news->have_posts()):
                    ?>
                        <div class="candidate-news-grid">
                            <?php while ($news->have_posts()): $news->the_post(); ?>
                                <a href="<?php the_permalink(); ?>" class="candidate-news-item">
                                    <div class="candidate-news-item__thumb">
                                        <?php if (has_post_thumbnail()): ?>
                                            <?php echo na_responsive_img(null, '120px', '', 'thumbnail'); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="candidate-news-item__content">
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

            <aside class="single-candidate__sidebar">
                <?php na_ad_slot('sidebar_top'); ?>

                <!-- Other candidates from same party -->
                <?php if ($current_party_id):
                    $party_candidates = na_get_party_candidates($current_party_id, 5);
                    if ($party_candidates->have_posts()):
                ?>
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget__title"><?php echo esc_html($current_party->post_title); ?> का नेताहरू</h3>
                        <?php while ($party_candidates->have_posts()): $party_candidates->the_post();
                            if (get_the_ID() === $candidate_id) continue;
                        ?>
                            <a href="<?php the_permalink(); ?>" class="sidebar-widget__candidate">
                                <?php echo get_avatar(null, 32); ?>
                                <?php if (has_post_thumbnail()): ?>
                                    <?php echo na_responsive_img(null, '32px', '', 'thumbnail', 'lazy', ['class' => 'sidebar-widget__candidate-img']); ?>
                                <?php endif; ?>
                                <span><?php the_title(); ?></span>
                            </a>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                <?php endif; endif; ?>

                <?php na_ad_slot('sidebar_sticky'); ?>
            </aside>
        </div>
    </div>

    <?php na_ad_slot('footer_banner', 'ad-slot--center'); ?>
</main>

<?php endwhile; endif;
get_footer();
?>
