<?php
/**
 * Search Results Template
 */
get_header();
?>

<main class="search-page">
    <div class="container">
        <header class="archive-page__header">
            <h1 class="archive-page__title">
                खोजी: "<?php echo esc_html(get_search_query()); ?>"
            </h1>
            <span class="archive-page__count">
                <?php echo $wp_query->found_posts; ?> नतिजा भेटियो
            </span>
        </header>

        <div class="archive-page__layout">
            <div class="archive-page__main">
                <?php if (have_posts()): ?>
                    <div class="archive-page__grid">
                        <?php while (have_posts()): the_post(); ?>
                            <article class="archive-card">
                                <a href="<?php the_permalink(); ?>" class="archive-card__link">
                                    <div class="archive-card__thumb">
                                        <?php if (has_post_thumbnail()): ?>
                                            <?php echo na_responsive_img(null, '(max-width:768px) 50vw, 33vw', '', 'medium'); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="archive-card__content">
                                        <span class="archive-card__category"><?php echo get_post_type_object(get_post_type())->labels->singular_name; ?></span>
                                        <h2 class="archive-card__title"><?php the_title(); ?></h2>
                                        <p class="archive-card__excerpt"><?php echo na_excerpt(120); ?></p>
                                        <span class="archive-card__time"><?php echo na_time_ago(); ?></span>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <nav class="archive-page__pagination">
                        <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '← अघिल्��ो', 'next_text' => 'अर्को →']); ?>
                    </nav>
                <?php else: ?>
                    <div class="archive-page__empty">
                        <h2>कुनै नतिजा भेटिएन</h2>
                        <p>कृपया अर्को शब्दमा खोज्नुहोस्।</p>
                        <?php get_search_form(); ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="archive-page__sidebar">
                <?php na_ad_slot('sidebar_top'); ?>
                <?php na_ad_slot('sidebar_sticky'); ?>
            </aside>
        </div>
    </div>
</main>

<?php
get_footer();
?>
