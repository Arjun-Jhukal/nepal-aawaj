<?php
/**
 * Main <article class="story"> — renders the current single post.
 * Pulls title, deck (excerpt), hero image, body (post_content), author bio,
 * tags, and breadcrumbs from the current loop post.
 */
if (! defined('ABSPATH')) exit;

if (! have_posts()) return;
the_post();

$post_id   = get_the_ID();
$cat       = na_primary_category($post_id);
$author_id = (int) get_post_field('post_author', $post_id);
$tags      = get_the_tags($post_id);
$updated   = (int) get_the_modified_time('U', $post_id) > (int) get_the_time('U', $post_id);
$words     = (int) ceil(str_word_count(strip_tags(get_the_content())) / 200);
$read_min  = max(1, $words);
$deck      = na_excerpt($post_id, 36);
?>
<article class="story">
    <div class="story-crumbs">
        <a href="<?php echo esc_url(home_url('/')); ?>">गृहपृष्ठ</a><span class="sep">›</span>
        <?php if ($cat['link']): ?>
            <a href="<?php echo esc_url($cat['link']); ?>"><?php echo esc_html($cat['np']); ?></a>
        <?php else: ?>
            <span><?php echo esc_html($cat['np']); ?></span>
        <?php endif; ?>
    </div>
    <?php if ($cat['np']): ?>
        <span class="story-tag"><?php echo esc_html($cat['np']); ?></span>
    <?php endif; ?>

    <h1 class="story-title"><?php the_title(); ?></h1>

    <?php if ($deck !== ''): ?>
        <p class="story-deck"><?php echo esc_html($deck); ?></p>
    <?php endif; ?>

    <div class="byline">
        <?php echo get_avatar($author_id, 44, '', '', array('class' => 'ava')); ?>
        <div class="info">
            <div class="name">
                <?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?>
                <?php $role = get_the_author_meta('description', $author_id); if ($role): ?>
                    · <?php echo esc_html(wp_trim_words($role, 4, '')); ?>
                <?php endif; ?>
            </div>
            <div class="meta en">
                PUBLISHED · <?php echo esc_html(get_the_date('M j, Y · H:i', $post_id)); ?>
                <?php if ($updated): ?>
                    · UPDATED · <?php echo esc_html(get_the_modified_date('H:i', $post_id)); ?>
                <?php endif; ?>
                · <?php echo (int) $read_min; ?> MIN READ
            </div>
        </div>
        <button class="follow" id="followBtn">+ फलो</button>
    </div>

    <?php if (has_post_thumbnail()):
        $caption = get_the_post_thumbnail_caption();
    ?>
        <div class="hero-image">
            <?php the_post_thumbnail('large', array('loading' => 'eager')); ?>
        </div>
        <?php if ($caption): ?>
            <p class="hero-caption"><strong>तस्बिर:</strong> <?php echo esc_html($caption); ?></p>
        <?php endif; ?>
    <?php else: ?>
        <div class="hero-image ph ph-red" data-label="<?php echo esc_attr($cat['np'] ?: get_the_title()); ?>"></div>
    <?php endif; ?>

    <div class="story-body" id="storyBody">
        <?php the_content(); ?>
    </div>

    <div class="story-share">
        <span class="story-share__label">यो खबर सेयर गर्नुहोस्</span>
        <?php get_template_part('template-parts/single/article-rail'); ?>
    </div>

    <?php if ($tags && ! is_wp_error($tags)): ?>
        <div class="story-tags">
            <span class="lab">सम्बन्धित शब्द:</span>
            <?php foreach ($tags as $tag): ?>
                <a class="tag" href="<?php echo esc_url(get_tag_link($tag)); ?>"><?php echo esc_html($tag->name); ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php
    $bio    = get_the_author_meta('description', $author_id);
    $name   = get_the_author_meta('display_name', $author_id);
    $email  = get_the_author_meta('user_email', $author_id);
    $url    = get_the_author_meta('user_url', $author_id);
    $twitter = get_the_author_meta('twitter', $author_id);
    if ($bio):
    ?>
        <div class="author-bio">
            <?php echo get_avatar($author_id, 64, '', '', array('class' => 'ava')); ?>
            <div>
                <?php if ($twitter): ?><div class="role"><?php echo esc_html($twitter); ?></div><?php endif; ?>
                <div class="name"><?php echo esc_html($name); ?></div>
                <div class="desc"><?php echo esc_html($bio); ?></div>
                <div style="margin-top: 8px; display: flex; gap: 8px; font-family: var(--f-ui); font-size: 11px;">
                    <?php if ($twitter): ?><a href="<?php echo esc_url('https://twitter.com/' . ltrim($twitter, '@')); ?>" style="color: var(--red);"><?php echo esc_html('@' . ltrim($twitter, '@')); ?></a> ·<?php endif; ?>
                    <?php if ($email): ?><a href="mailto:<?php echo esc_attr($email); ?>" style="color: var(--gray-6);"><?php echo esc_html($email); ?></a> ·<?php endif; ?>
                    <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" style="color: var(--gray-6);">सबै लेख →</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (comments_open() || get_comments_number()): ?>
        <?php get_template_part('template-parts/single/comments'); ?>
    <?php endif; ?>
</article>
