<?php
/**
 * Block: Contact Us — Hours.
 *
 * Working hours + emergency contact strip. Heading + rows (label + hours)
 * plus an optional emergency line for after-hours news tips.
 */
if (! defined('ABSPATH')) exit;

$heading_np = na_field('heading_np', null, 'कार्य समय');
$heading_en = na_field('heading_en', null, 'Working Hours');
$rows       = na_field('rows', null, array(
    array('label' => 'सम्पादकीय', 'hours' => 'आइतबार – शुक्रबार · ९:०० – ६:००'),
    array('label' => 'विज्ञापन',  'hours' => 'आइतबार – शुक्रबार · १०:०० – ५:००'),
));
$emergency_label = na_field('emergency_label', null, 'आपत्कालीन सूचना');
$emergency_value = na_field('emergency_value', null, '+977 9800000000');
?>
<section class="container-wide contact-hours">
    <div class="section-head">
        <h2><?php echo esc_html($heading_np); ?> <span class="en-sub"><?php echo esc_html($heading_en); ?></span></h2>
    </div>
    <div class="hours-grid">
        <?php foreach ((array) $rows as $r): ?>
            <div class="hours-row">
                <span class="hours-row__label"><?php echo esc_html($r['label'] ?? ''); ?></span>
                <span class="hours-row__hours"><?php echo esc_html($r['hours'] ?? ''); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($emergency_label || $emergency_value): ?>
        <div class="hours-emergency">
            <?php if ($emergency_label): ?><span class="hours-emergency__label"><?php echo esc_html($emergency_label); ?></span><?php endif; ?>
            <?php if ($emergency_value): ?><span class="hours-emergency__value en"><?php echo esc_html($emergency_value); ?></span><?php endif; ?>
        </div>
    <?php endif; ?>
</section>
