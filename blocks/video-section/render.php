<?php
/**
 * Block: Multimedia (video-section).
 *
 * ACF fields (group_block_video_section):
 *   - title_np / title_en / more_link
 *   - featured_video (group: post|external_url, duration, thumbnail, label)
 *   - tiles          (repeater × 4: post|external_url, kind, duration, label)
 *
 * `kind` enum: video | podcast | gallery | live.
 */
if (! defined('ABSPATH')) exit;

if (! function_exists('na_video_tile')):
function na_video_tile($t) {
    $url   = $t['url']   ?? '#';
    $kind  = $t['kind']  ?? 'video';
    $label = $t['label'] ?? '';
    $title = $t['title'] ?? '';
    $meta  = $t['meta']  ?? '';
    $tag   = $t['tag']   ?? '';
    ?>
    <a href="<?php echo esc_url($url); ?>" class="mm-card">
        <div class="thumb ph ph-dark" data-label="<?php echo esc_attr($label); ?>">
            <?php if ($kind === 'live'): ?>
                <div class="play"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
                <div class="cat-tag" style="background: var(--red);">● लाइभ</div>
            <?php elseif ($kind === 'podcast'): ?>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="position:absolute;right:12px;top:12px;color:var(--red);"><path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.91-3c-.49 0-.9.36-.98.85-.41 2.51-2.6 4.15-4.93 4.15s-4.52-1.64-4.93-4.15c-.08-.49-.49-.85-.98-.85-.61 0-1.09.54-1 1.14.49 3.04 2.99 5.42 5.91 5.79V20c0 .55.45 1 1 1s1-.45 1-1v-2.06c2.92-.37 5.42-2.75 5.91-5.79.1-.6-.39-1.14-1-1.14z"/></svg>
            <?php elseif ($kind === 'gallery'): ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;right:12px;top:12px;color:var(--red);"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            <?php else: ?>
                <div class="play"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
                <?php if ($tag): ?><div class="cat-tag"><?php echo esc_html($tag); ?></div><?php endif; ?>
            <?php endif; ?>
        </div>
        <h3 class="title"><?php echo esc_html($title); ?></h3>
        <div class="meta"><?php echo esc_html($meta); ?></div>
    </a>
    <?php
}
endif;

if (! function_exists('na_video_section_default_tiles')):
function na_video_section_default_tiles() {
    return array(
        array('kind' => 'video',   'title' => 'मन्त्रीसँग प्रत्यक्ष कुराकानी: नीति, विवाद र भविष्य', 'meta' => 'अन्तर्वार्ता', 'label' => 'Interview · 18:42'),
        array('kind' => 'podcast', 'title' => 'पोडकास्ट: डिजिटल नेपालको दशक, के पायौँ के गुमायौँ?',   'meta' => '४५ मिनेटको पोडकास्ट', 'label' => 'Podcast · 45:12'),
        array('kind' => 'gallery', 'title' => 'तस्बिरमा देखियो: होलीको रौनकमा रंगिएको साँझ',          'meta' => '२४ तस्बिर',          'label' => 'Photo gallery · 24 ph'),
        array('kind' => 'live',    'title' => 'संसद बैठक प्रत्यक्ष प्रसारण',                        'meta' => 'अहिले लाइभ',          'label' => 'Live · 03:24:18'),
    );
}
endif;

$title_np  = na_field('title_np',  null, 'मल्टिमिडिया');
$title_en  = na_field('title_en',  null, 'Video · Audio · Photo');
$more_link = na_field('more_link', null, '');
$featured  = na_field('featured_video', null, array());
$tiles     = na_field('tiles', null, array());

if (! $tiles) {
    $tiles = na_video_section_default_tiles();
}
$tiles = array_slice((array) $tiles, 0, 4);
?>
<section class="multimedia">
    <div class="container-wide">
        <div class="section-head">
            <h2><?php echo esc_html($title_np); ?> <span class="en-sub"><?php echo esc_html($title_en); ?></span></h2>
            <?php if ($more_link): ?><a href="<?php echo esc_url($more_link); ?>" class="more">सबै हेर्नुहोस् →</a><?php endif; ?>
        </div>
        <div class="multimedia-grid">
            <a href="<?php echo esc_url($featured['url'] ?? '#'); ?>" class="mm-card large">
                <div class="thumb ph ph-red" data-label="<?php echo esc_attr($featured['label'] ?? 'Featured video'); ?>">
                    <div class="play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
                    <?php if (! empty($featured['tag'])): ?><div class="cat-tag"><?php echo esc_html($featured['tag']); ?></div><?php endif; ?>
                </div>
                <h3 class="title"><?php echo esc_html($featured['title'] ?? 'हाम्रो हिमाल, हाम्रो भविष्य: जलवायु परिवर्तनको असरमा हिमाली जिल्ला'); ?></h3>
                <div class="meta"><?php echo esc_html($featured['meta'] ?? '२८ मिनेटको डकुमेन्ट्री'); ?></div>
            </a>

            <div style="display:flex; flex-direction:column; gap: 20px;">
                <?php foreach (array_slice($tiles, 0, 2) as $t): na_video_tile($t); endforeach; ?>
            </div>
            <div style="display:flex; flex-direction:column; gap: 20px;">
                <?php foreach (array_slice($tiles, 2, 2) as $t): na_video_tile($t); endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php

