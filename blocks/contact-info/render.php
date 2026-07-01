<?php
/**
 * Block: Contact Info.
 *
 * ACF fields (group_block_contact_info):
 *   - heading_np / heading_en
 *   - address, phone, email
 *   - hours (textarea)
 *   - map_embed (textarea)  raw iframe HTML
 *   - social   (group): facebook, twitter, youtube, instagram, whatsapp
 */
if (! defined('ABSPATH')) exit;

$heading_np = na_field('heading_np', null, 'सम्पर्क');
$heading_en = na_field('heading_en', null, 'Get in touch');
$address    = na_field('address',    null, 'पुतलीसडक, काठमाडौं, नेपाल');
$phone      = na_field('phone',      null, '+977-1-4444555');
$email      = na_field('email',      null, 'hello@nepalaawaj.com');
$hours      = na_field('hours',      null, "आइतबार – शुक्रबार\n९:०० बिहान – ६:०० बेलुका");
$map        = na_field('map_embed',  null, '');
$social     = na_field('social',     null, array());
?>
<section class="section contact-info-block">
    <div class="container-wide">
        <div class="contact-info-grid">
            <div>
                <span class="kicker"><?php echo esc_html($heading_en); ?></span>
                <h2><?php echo esc_html($heading_np); ?></h2>

                <ul class="contact-list">
                    <li><?php echo na_icon('pin'); ?> <span><?php echo esc_html($address); ?></span></li>
                    <li><?php echo na_icon('phone'); ?> <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></li>
                    <li><?php echo na_icon('email'); ?> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></li>
                </ul>

                <h3 class="contact-subhead">कार्य समय</h3>
                <p class="contact-hours"><?php echo nl2br(esc_html($hours)); ?></p>

                <?php if ($social): ?>
                    <div class="contact-social">
                        <?php foreach (array('fb' => 'facebook', 'tw' => 'twitter', 'yt' => 'youtube', 'ig' => 'instagram', 'whatsapp' => 'whatsapp') as $icon => $key): ?>
                            <?php if (! empty($social[$key])): ?>
                                <a href="<?php echo esc_url($social[$key]); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($key); ?>"><?php echo na_icon($icon); ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="contact-map">
                <?php if ($map) echo $map; else echo '<div class="ph" style="aspect-ratio: 4/3;" data-label="Map · Office location"></div>'; ?>
            </div>
        </div>
    </div>
</section>
