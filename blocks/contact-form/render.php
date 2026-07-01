<?php
/**
 * Block: Contact Us — Form.
 *
 * Heading + intro + a form. Form source: a Contact Form 7 shortcode (or any
 * shortcode), or raw HTML for editors who use a third-party embed. CF7 is the
 * intended default — the editor pastes its shortcode into the `shortcode`
 * field and the styling in _contact.scss takes care of the rest.
 */
if (! defined('ABSPATH')) exit;

$heading   = na_field('heading',   null, 'सम्पर्क फारम');
$intro     = na_field('intro',     null, 'तपाईंको प्रतिक्रिया, सुझाव वा समाचार सूचना तल पठाउनुहोस्।');
$shortcode = na_field('shortcode', null, '');
$html      = na_field('html_embed', null, '');
?>
<section class="container-wide contact-form-block">
    <div class="contact-form-block__head">
        <h2><?php echo esc_html($heading); ?></h2>
        <?php if ($intro): ?><p class="contact-form-block__intro"><?php echo esc_html($intro); ?></p><?php endif; ?>
    </div>
    <div class="contact-form-block__form">
        <?php if ($shortcode) {
            echo do_shortcode($shortcode);
        } elseif ($html) {
            echo wp_kses_post($html);
        } else { ?>
        <div class="contact-form-block__placeholder">
            <strong>Contact Form 7 shortcode goes here.</strong><br>
            Paste a shortcode like <code>[contact-form-7 id="123" title="Contact"]</code> into the block's
            <em>Shortcode</em> field.
        </div>
        <?php } ?>
    </div>
</section>