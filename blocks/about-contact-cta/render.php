<?php
/**
 * Block: About Us — Contact CTA.
 *
 * Bottom-of-page CTA panel: headline + body + 1-2 buttons.
 */
if (! defined('ABSPATH')) exit;

$heading = na_field('heading', null, 'समाचार सुझाव छ?');
$body    = na_field('body',    null, 'तपाईंको कथा हाम्रो टोलीसँग साझा गर्नुहोस्।');
$btn1_l  = na_field('button_label',     null, 'सम्पर्क गर्नुहोस्');
$btn1_u  = na_field('button_url',       null, '/contact');
$btn2_l  = na_field('button_label_2',   null, '');
$btn2_u  = na_field('button_url_2',     null, '');
?>
<section class="container-wide about-cta">
    <div class="cta-panel">
        <h2 class="cta-panel__heading"><?php echo esc_html($heading); ?></h2>
        <?php if ($body): ?><p class="cta-panel__body"><?php echo esc_html($body); ?></p><?php endif; ?>
        <div class="cta-panel__buttons">
            <?php if ($btn1_l): ?><a href="<?php echo esc_url($btn1_u ?: '#'); ?>" class="btn btn-red"><?php echo esc_html($btn1_l); ?></a><?php endif; ?>
            <?php if ($btn2_l): ?><a href="<?php echo esc_url($btn2_u ?: '#'); ?>" class="btn btn-ghost"><?php echo esc_html($btn2_l); ?></a><?php endif; ?>
        </div>
    </div>
</section>
