<?php
/** Document meta strip — version + effective date + last update + reading time.
 *
 *   get_template_part('template-parts/legal/meta-bar', null, array(
 *       'version'   => 'v3.2',
 *       'effective' => 'May 1, 2026',
 *       'updated'   => 'May 21, 2026',
 *       'reading'   => '८ मिनेट',
 *   ));
 */
if (! defined('ABSPATH')) exit;

$version   = isset($args['version'])   ? (string) $args['version']   : '';
$effective = isset($args['effective']) ? (string) $args['effective'] : '';
$updated   = isset($args['updated'])   ? (string) $args['updated']   : '';
$reading   = isset($args['reading'])   ? (string) $args['reading']   : '';
?>
<div class="meta-bar">
    <?php if ($version) : ?>
        <div class="item"><strong>संस्करण:</strong> <span class="en"><?php echo esc_html($version); ?></span></div>
    <?php endif; ?>
    <?php if ($effective) : ?>
        <div class="item"><strong>प्रभावी मिति:</strong> <span class="en"><?php echo esc_html($effective); ?></span></div>
    <?php endif; ?>
    <?php if ($updated) : ?>
        <div class="item"><strong>अन्तिम अपडेट:</strong> <span class="en"><?php echo esc_html($updated); ?></span></div>
    <?php endif; ?>
    <?php if ($reading) : ?>
        <div class="item"><strong>अनुमानित पढाइ:</strong> <?php echo esc_html($reading); ?></div>
    <?php endif; ?>
</div>
