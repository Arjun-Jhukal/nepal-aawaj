<?php
/**
 * Block: About Us — Timeline.
 *
 * Founding-story milestones: heading + events (year, title, body).
 */
if (! defined('ABSPATH')) exit;

$heading_np = na_field('heading_np', null, 'हाम्रो यात्रा');
$heading_en = na_field('heading_en', null, 'Our Journey');
$events     = na_field('events', null, array());
?>
<section class="container-wide about-timeline">
    <div class="section-head">
        <h2><?php echo esc_html($heading_np); ?> <span class="en-sub"><?php echo esc_html($heading_en); ?></span></h2>
    </div>
    <ol class="timeline">
        <?php foreach ((array) $events as $e): ?>
            <li class="timeline__event">
                <?php if (! empty($e['year'])):  ?><div class="timeline__year"><?php echo esc_html($e['year']); ?></div><?php endif; ?>
                <?php if (! empty($e['title'])): ?><h3 class="timeline__title"><?php echo esc_html($e['title']); ?></h3><?php endif; ?>
                <?php if (! empty($e['body'])):  ?><p class="timeline__body"><?php echo esc_html($e['body']); ?></p><?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</section>
