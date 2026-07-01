<?php
/**
 * Block: About Us — Team.
 *
 * Team / leadership grid: heading + member cards (image, name, role, bio).
 */
if (! defined('ABSPATH')) exit;

$heading_np = na_field('heading_np', null, 'टोली');
$heading_en = na_field('heading_en', null, 'Our Team');
$members    = na_field('members', null, array());
?>
<section class="container-wide about-team">
    <div class="section-head">
        <h2><?php echo esc_html($heading_np); ?> <span class="en-sub"><?php echo esc_html($heading_en); ?></span></h2>
    </div>
    <div class="team-grid">
        <?php foreach ((array) $members as $m):
            $img = na_image_data($m['image'] ?? null);
        ?>
            <div class="team-card">
                <div class="team-card__photo">
                    <?php if ($img): ?>
                        <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt'] ?: ($m['name'] ?? '')); ?>">
                    <?php else: ?>
                        <div class="ph" style="aspect-ratio: 1/1;" data-label="<?php echo esc_attr($m['name'] ?? ''); ?>"></div>
                    <?php endif; ?>
                </div>
                <?php if (! empty($m['name'])): ?><h3 class="team-card__name"><?php echo esc_html($m['name']); ?></h3><?php endif; ?>
                <?php if (! empty($m['role'])): ?><div class="team-card__role"><?php echo esc_html($m['role']); ?></div><?php endif; ?>
                <?php if (! empty($m['bio'])):  ?><p class="team-card__bio"><?php echo esc_html($m['bio']); ?></p><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
