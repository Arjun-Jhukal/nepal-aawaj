<?php
/**
 * Masthead — left ad slot, centered brand, right ad slot.
 *
 * Branding rule: when a logo image is uploaded in Theme Settings, show
 * ONLY the logo. Otherwise show the site name + tagline text.
 */
if (! defined('ABSPATH')) exit;

$ts_logo    = na_image_data(na_option('ts_logo'));
$ts_name    = na_option('ts_site_name',    get_bloginfo('name'));
$ts_tagline = na_option('ts_site_tagline', get_bloginfo('description'));
?>
<div class="masthead">
    <div class="container-wide inner">
        <div class="ad-slot">Advertisement · 728 × 90</div>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="brand">
            <?php if ($ts_logo):
                $w = $ts_logo['width']  > 0 ? $ts_logo['width']  : 56;
                $h = $ts_logo['height'] > 0 ? $ts_logo['height'] : 56;
            ?>
                <div class="brand-logo">
                    <img src="<?php echo esc_url($ts_logo['url']); ?>" alt="<?php echo esc_attr($ts_logo['alt'] ?: $ts_name); ?>" width="<?php echo esc_attr($w); ?>" height="<?php echo esc_attr($h); ?>">
                </div>
            <?php else: ?>
                <div class="brand-text">
                    <?php if ($ts_name):    ?><div class="brand-name"><?php echo esc_html($ts_name); ?></div><?php endif; ?>
                    <?php if ($ts_tagline): ?><div class="brand-tagline"><?php echo esc_html($ts_tagline); ?></div><?php endif; ?>
                </div>
            <?php endif; ?>
        </a>
        <div class="ad-slot right">Advertisement · 728 × 90</div>
    </div>
</div>
