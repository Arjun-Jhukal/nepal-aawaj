<?php
/**
 * Utility bar — date + data pills (left), language toggle + social (right).
 * Date, pills, and social URLs are pulled from Theme Settings (ACF Options).
 */
if (! defined('ABSPATH')) exit;

if (! na_option('ts_ub_show', true)) return;

$date  = na_option('ts_ub_date', 'जेठ ७, २०८३ · बिहीबार');
$pills = na_option('ts_ub_pills', array());
?>
<div class="utility-bar">
    <div class="container-wide inner">
        <div class="left">
            <?php if ($date): ?><span class="date"><?php echo esc_html($date); ?></span><?php endif; ?>
            <?php if ($pills): ?><span class="dot"></span><?php endif; ?>
            <?php foreach ((array) $pills as $pill):
                $label = isset($pill['label']) ? $pill['label'] : '';
                $value = isset($pill['value']) ? $pill['value'] : '';
                $dir   = isset($pill['delta_dir']) ? $pill['delta_dir'] : '';
                $dval  = isset($pill['delta_val']) ? $pill['delta_val'] : '';
            ?>
                <span class="en data-pill">
                    <?php if ($label !== ''): ?><span class="label"><?php echo esc_html($label); ?></span><?php endif; ?>
                    <?php if ($value !== ''): ?><span class="val"><?php echo esc_html($value); ?></span><?php endif; ?>
                    <?php if ($dir === 'up'   && $dval !== ''): ?><span class="delta-up">▲ <?php echo esc_html($dval); ?></span><?php endif; ?>
                    <?php if ($dir === 'down' && $dval !== ''): ?><span class="delta-down">▼ <?php echo esc_html($dval); ?></span><?php endif; ?>
                </span>
            <?php endforeach; ?>
        </div>
        <div class="right">
            <span class="lang-toggle">
                <button class="active">नेप</button>
                <button>EN</button>
            </span>
            <span class="social">
                <?php $fb = na_option('ts_facebook_url');  if ($fb)  : ?><a href="<?php echo esc_url($fb); ?>" aria-label="Facebook" target="_blank" rel="noopener"><?php echo na_icon('fb'); ?></a><?php endif; ?>
                <?php $tw = na_option('ts_twitter_url');   if ($tw)  : ?><a href="<?php echo esc_url($tw); ?>" aria-label="X/Twitter" target="_blank" rel="noopener"><?php echo na_icon('tw'); ?></a><?php endif; ?>
                <?php $yt = na_option('ts_youtube_url');   if ($yt)  : ?><a href="<?php echo esc_url($yt); ?>" aria-label="YouTube" target="_blank" rel="noopener"><?php echo na_icon('yt'); ?></a><?php endif; ?>
                <?php $ig = na_option('ts_instagram_url'); if ($ig)  : ?><a href="<?php echo esc_url($ig); ?>" aria-label="Instagram" target="_blank" rel="noopener"><?php echo na_icon('ig'); ?></a><?php endif; ?>
                <?php $wa = na_option('ts_whatsapp_url');  if ($wa)  : ?><a href="<?php echo esc_url($wa); ?>" aria-label="WhatsApp" target="_blank" rel="noopener"><?php echo na_icon('whatsapp'); ?></a><?php endif; ?>
            </span>
        </div>
    </div>
</div>
