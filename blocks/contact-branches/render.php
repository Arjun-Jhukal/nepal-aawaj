<?php
/**
 * Block: Contact Us — Branches.
 *
 * Heading + grid of branch cards (city, address, phone, email, hours).
 */
if (! defined('ABSPATH')) exit;

$heading_np = na_field('heading_np', null, 'हाम्रा शाखाहरू');
$heading_en = na_field('heading_en', null, 'Our Branches');
$branches   = na_field('branches', null, array());
?>
<section class="container-wide contact-branches">
    <div class="section-head">
        <h2><?php echo esc_html($heading_np); ?> <span class="en-sub"><?php echo esc_html($heading_en); ?></span></h2>
    </div>
    <div class="branches-grid">
        <?php foreach ((array) $branches as $b): ?>
            <div class="branch-card">
                <?php if (! empty($b['city'])):    ?><h3 class="branch-card__city"><?php echo esc_html($b['city']); ?></h3><?php endif; ?>
                <?php if (! empty($b['address'])): ?><div class="branch-card__row"><?php echo na_icon('pin'); ?><span><?php echo esc_html($b['address']); ?></span></div><?php endif; ?>
                <?php if (! empty($b['phone'])):   ?><div class="branch-card__row"><?php echo na_icon('phone'); ?><a class="en" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $b['phone'])); ?>"><?php echo esc_html($b['phone']); ?></a></div><?php endif; ?>
                <?php if (! empty($b['email'])):   ?><div class="branch-card__row"><?php echo na_icon('email'); ?><a class="en" href="mailto:<?php echo esc_attr($b['email']); ?>"><?php echo esc_html($b['email']); ?></a></div><?php endif; ?>
                <?php if (! empty($b['hours'])):   ?><div class="branch-card__hours"><?php echo nl2br(esc_html($b['hours'])); ?></div><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
