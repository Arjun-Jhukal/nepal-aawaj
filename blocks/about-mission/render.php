<?php
/**
 * Block: About Us — Mission.
 *
 * Section heading plus N "mission cards" (number, title, body).
 */
if (! defined('ABSPATH')) exit;

$heading_np = na_field('heading_np', null, 'हाम्रो लक्ष्य');
$heading_en = na_field('heading_en', null, 'Our Mission');
$cards      = na_field('cards', null, array(
    array('num' => '०१', 'title' => 'सत्य पत्रकारिता', 'body' => 'तथ्यमा आधारित, प्रमाणित स्रोतबाट प्राप्त समाचार मात्र प्रकाशित गर्ने।'),
    array('num' => '०२', 'title' => 'निष्पक्षता',     'body' => 'कुनै राजनीतिक दल वा स्वार्थको प्रभावमा नपरी समाचार लेख्ने।'),
    array('num' => '०३', 'title' => 'राष्ट्रको आवाज', 'body' => 'देशका हरेक कुनामा रहेका नागरिकको आवाजलाई मूलधारमा ल्याउने।'),
));
?>
<section class="container-wide about-mission">
    <div class="section-head">
        <h2><?php echo esc_html($heading_np); ?> <span class="en-sub"><?php echo esc_html($heading_en); ?></span></h2>
    </div>
    <div class="mission-grid">
        <?php foreach ((array) $cards as $card): ?>
            <div class="mission-card">
                <?php if (! empty($card['num'])):   ?><span class="num"><?php echo esc_html($card['num']); ?></span><?php endif; ?>
                <?php if (! empty($card['title'])): ?><h3><?php echo esc_html($card['title']); ?></h3><?php endif; ?>
                <?php if (! empty($card['body'])):  ?><p><?php echo esc_html($card['body']); ?></p><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
