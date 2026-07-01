<?php
/**
 * Magazine-style listing — runs the main WP_Query loop.
 * Skips the first post (handled by feature-story.php). Save button is wired
 * to localStorage by archive/scripts.php.
 */
if (! defined('ABSPATH')) exit;

if (! have_posts()) {
    echo '<div class="empty-state" style="padding:32px 0;color:var(--gray-7);">यो श्रेणीमा अहिले समाचार उपलब्ध छैन।</div>';
    return;
}
?>
<div id="listing">
    <?php
    $i = 0;
    while (have_posts()): the_post();
        if ($i++ === 0) continue; // skip the feature post
        $cat   = na_primary_category(get_the_ID());
        $views = (int) get_post_meta(get_the_ID(), 'na_view_count', true);
        $words = (int) ceil(str_word_count(strip_tags(get_the_content())) / 200);
        $read  = max(1, $words);
    ?>
    <article class="listing-item card">
        <?php echo na_thumb(get_post(), '4/3', 'medium', '(max-width:768px) 100vw, 30vw', 'lazy', array(
            'label'   => $cat['np'] ?: '',
            'cat_tag' => $cat['np'] ?: '',
        )); ?>
        <div>
            <div class="kicker"><?php echo esc_html($cat['np']); ?></div>
            <h3 class="title"><?php the_title(); ?></h3>
            <p class="excerpt"><?php echo esc_html(na_excerpt(get_the_ID(), 36)); ?></p>
            <div class="meta">
                <span class="author"><?php the_author(); ?></span>
                <span class="dot"></span><span><?php echo esc_html(na_time_ago(get_the_ID())); ?></span>
                <span class="dot"></span><span><?php echo esc_html(na_to_devanagari($read)); ?> मिनेट पढाइ</span>
                <?php if ($views): ?>
                    <span class="dot"></span><span class="views">● <?php echo esc_html(na_to_devanagari(number_format($views))); ?> हेरिएको</span>
                <?php endif; ?>
                <button class="save-btn" data-id="<?php echo esc_attr(get_the_ID()); ?>">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                    सेभ
                </button>
            </div>
        </div>
    </article>
    <?php endwhile; wp_reset_postdata(); ?>
</div>
