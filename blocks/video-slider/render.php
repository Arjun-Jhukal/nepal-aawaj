<?php
/**
 * Block: Video Slider.
 *
 * ACF fields (group_block_video_slider):
 *   - title
 *   - videos (repeater): { url, thumbnail (image), label, duration }
 *
 * Markup is a plain horizontal-scroll list; the front-end JS (shared.min.js)
 * upgrades it to a snap-scroll slider with prev/next controls when it sees
 * `data-na-slider="video"`.
 */
if (! defined('ABSPATH')) exit;

$title  = na_field('title',  null, 'भिडियो');
$videos = na_field('videos', null, array());
if (! $videos) {
    $videos = array(
        array('label' => 'Featured · 12:34', 'duration' => '12:34'),
        array('label' => 'Interview · 18:42', 'duration' => '18:42'),
        array('label' => 'Podcast · 45:12',   'duration' => '45:12'),
        array('label' => 'Photo gallery · 24', 'duration' => ''),
    );
}
?>
<section class="section video-slider">
    <div class="container-wide">
        <div class="section-head"><h2><?php echo esc_html($title); ?></h2></div>
        <div class="video-slider__track" data-na-slider="video">
            <?php foreach ((array) $videos as $v): ?>
                <a href="<?php echo esc_url($v['url'] ?? '#'); ?>" class="video-slider__item">
                    <div class="thumb ph ph-dark" data-label="<?php echo esc_attr($v['label'] ?? ''); ?>">
                        <div class="play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
                        <?php if (! empty($v['duration'])): ?><span class="duration"><?php echo esc_html($v['duration']); ?></span><?php endif; ?>
                    </div>
                    <h4 class="title"><?php echo esc_html($v['title'] ?? ($v['label'] ?? '')); ?></h4>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
