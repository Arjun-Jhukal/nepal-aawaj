<?php
/**
 * Breaking-news ticker. Items pulled from Theme Settings; the list is
 * duplicated inline so the CSS @keyframes can loop seamlessly via
 * `translateX(-50%)`.
 */
if (! defined('ABSPATH')) exit;

if (! na_option('ts_ticker_show', true)) return;

$label  = na_option('ts_ticker_label', 'ब्रेकिङ');
$update = na_option('ts_ticker_update', '');
$items  = na_option('ts_ticker_items', array());

if (! $items) {
    foreach (array(
        'संसद बैठक स्थगित: बजेट अधिवेशनमा थप तनाव',
        'नेपाली रुपैयाँ डलरसँग रु. १३२.५० मा',
        'हिमाल आरोहणमा शेर्पा साथीहरूको नयाँ कीर्तिमान',
        'काठमाडौंमा वायु प्रदूषणको स्तर खतरनाक',
        'विश्वकपका लागि नेपाली टोली घोषणा',
        'नयाँ डिजिटल सेवा सञ्चालनमा',
    ) as $text) {
        $items[] = array('text' => $text, 'url' => '#');
    }
}

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
    <?php if ($update !== ''): ?><div class="ts en"><?php echo esc_html($update); ?></div><?php endif; ?>
</div>
