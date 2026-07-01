<?php
/**
 * Block: About Us — Principles.
 *
 * Editorial principles grid: 4-6 cards with title + body, optional icon name.
 */
if (! defined('ABSPATH')) exit;

$heading_np = na_field('heading_np', null, 'सम्पादकीय सिद्धान्त');
$heading_en = na_field('heading_en', null, 'Editorial Principles');
$items      = na_field('items', null, array(
    array('title' => 'पारदर्शिता', 'body' => 'समाचारको स्रोत स्पष्ट खुलाउने।'),
    array('title' => 'जवाफदेहिता', 'body' => 'त्रुटि भएमा तुरुन्तै सच्याउने।'),
    array('title' => 'स्वतन्त्रता', 'body' => 'सम्पादकीय स्वतन्त्रतालाई सर्वोपरि राख्ने।'),
    array('title' => 'सम्मान',     'body' => 'विविध दृष्टिकोणलाई समान स्थान दिने।'),
));
?>
<section class="container-wide about-principles">
    <div class="section-head">
        <h2><?php echo esc_html($heading_np); ?> <span class="en-sub"><?php echo esc_html($heading_en); ?></span></h2>
    </div>
    <div class="principles-grid">
        <?php foreach ((array) $items as $item): ?>
            <div class="principle-card">
                <?php if (! empty($item['title'])): ?><h3><?php echo esc_html($item['title']); ?></h3><?php endif; ?>
                <?php if (! empty($item['body'])):  ?><p><?php echo esc_html($item['body']); ?></p><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
