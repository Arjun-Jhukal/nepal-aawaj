<?php
/**
 * Block: About Us — Intro.
 *
 * Hero-style intro for the About page: eyebrow + headline + lead paragraph.
 */
if (! defined('ABSPATH')) exit;

$eyebrow = na_field('eyebrow', null, 'About Us');
$heading = na_field('heading', null, 'राष्ट्रिय आवाज');
$lead    = na_field('lead',    null, 'सत्य, निष्पक्ष र विश्वसनीय समाचारको माध्यमबाट लोकतन्त्रको रक्षा।');
?>
<section class="container-wide about-intro">
    <?php if ($eyebrow): ?><div class="kicker about-intro__eyebrow"><?php echo esc_html($eyebrow); ?></div><?php endif; ?>
    <h1 class="about-intro__heading"><?php echo esc_html($heading); ?></h1>
    <?php if ($lead): ?><p class="about-intro__lead"><?php echo esc_html($lead); ?></p><?php endif; ?>
</section>
