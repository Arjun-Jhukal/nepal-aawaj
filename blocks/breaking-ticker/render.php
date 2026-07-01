<?php
/**
 * Block: Breaking News Ticker.
 *
 * ACF fields (group_block_breaking_ticker):
 *   - enabled (true_false)
 *   - label   (string, e.g. "ब्रेकिङ")
 *   - mode    (radio: manual | latest)
 *   - items   (repeater): { text, url }   — manual mode.
 *   - count   (number)                    — latest mode (most-recent posts).
 *
 * Uses the same .ticker markup as template-parts/header/ticker.php so the
 * existing CSS keyframe scroll applies without per-block styling.
 */
if (! defined('ABSPATH')) exit;

if (! (bool) na_field('enabled', null, true)) return;

$label = na_field('label', null, 'ब्रेकिङ');
$mode  = na_field('mode',  null, 'latest');
$items = array();

if ($mode === 'manual') {
    $items = na_field('items', null, array());
} else {
    $count = (int) na_field('count', null, 6);
    foreach (get_posts(array('numberposts' => $count ?: 6)) as $p) {
        $items[] = array('text' => get_the_title($p), 'url' => get_permalink($p));
    }
}

if (! $items) {
    $items = array(
        array('text' => 'संसद बैठक सुरु, तेस्रो दिन पनि अवरुद्ध', 'url' => '#'),
        array('text' => 'सुनको मूल्य रेकर्ड उच्चतम तहमा',          'url' => '#'),
        array('text' => 'विश्वकपमा नेपालको पहिलो खेल आज',         'url' => '#'),
    );
}

// Duplicate the list so the CSS @keyframes can loop seamlessly via translateX(-50%).
$doubled = array_merge($items, $items);
?>
<div class="ticker">
    <div class="label"><span class="pulse"></span><?php echo esc_html($label); ?></div>
    <div class="track">
        <div class="run">
            <?php foreach ($doubled as $item):
                $text = isset($item['text']) ? $item['text'] : '';
                $url  = ! empty($item['url']) ? $item['url'] : '#';
                if ($text === '') continue;
            ?>
                <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($text); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
