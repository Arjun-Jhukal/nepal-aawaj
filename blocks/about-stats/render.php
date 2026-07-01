<?php
/**
 * Block: About Us — Stats.
 *
 * Big-number stat strip: heading + N counters (value + suffix + label).
 */
if (! defined('ABSPATH')) exit;

$heading_np = na_field('heading_np', null, 'तथ्याङ्क');
$heading_en = na_field('heading_en', null, 'In Numbers');
$stats      = na_field('stats', null, array(
    array('value' => '१२', 'suffix' => '+',  'label' => 'वर्षको अनुभव'),
    array('value' => '२५', 'suffix' => '',   'label' => 'जिल्ला सम्बाददाता'),
    array('value' => '१M', 'suffix' => '+',  'label' => 'मासिक पाठक'),
    array('value' => '१००', 'suffix' => '+', 'label' => 'पुरस्कारित रिपोर्ट'),
));
?>
<section class="container-wide about-stats">
    <div class="section-head">
        <h2><?php echo esc_html($heading_np); ?> <span class="en-sub"><?php echo esc_html($heading_en); ?></span></h2>
    </div>
    <div class="stats-grid">
        <?php foreach ((array) $stats as $stat): ?>
            <div class="stat">
                <div class="stat__num"><?php echo esc_html($stat['value'] ?? ''); ?><?php echo esc_html($stat['suffix'] ?? ''); ?></div>
                <?php if (! empty($stat['label'])): ?><div class="stat__label"><?php echo esc_html($stat['label']); ?></div><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
