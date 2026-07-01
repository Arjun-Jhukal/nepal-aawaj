<?php
/**
 * Block: Ad Slot.
 *
 * ACF fields (group_block_ad_slot):
 *   - slot_key     (select)   — header_banner | in_article | sidebar_top | sidebar_sticky | footer_banner | custom
 *   - aspect_ratio (string)   — used when slot_key = "custom" (e.g. "1/1.2", "4/3")
 *   - label        (string)   — placeholder text
 *   - ad_code      (textarea) — raw HTML/JS ad code; trumps image when set
 *   - ad_image     (image)    — fallback static banner
 *   - link         (url)      — wraps the image when present
 *
 * If ad_code is set we trust it as-is (raw HTML, e.g. AdSense). Otherwise
 * we render the image (with optional link) or fall back to the placeholder.
 */
if (! defined('ABSPATH')) exit;

$slot   = na_field('slot_key',     null, 'in_article');
$aspect = na_field('aspect_ratio', null, '');
$label  = na_field('label',        null, '');
$code   = na_field('ad_code',      null, '');
$image  = na_field('ad_image',     null, null);
$link   = na_field('link',         null, '');

if ($code) {
    // Raw ad code — print as-is. Trust the editor.
    echo '<div class="ad-slot ad-slot--code" data-slot="' . esc_attr($slot) . '">' . $code . '</div>';
    return;
}

if ($image && is_array($image)) {
    // Use the slot's expected aspect ratio so the layout slot is reserved
    // before the image paints — avoids cumulative layout shift on ads.
    $aspect_map = array(
        'header_banner'  => '970/90',
        'in_article'     => '728/90',
        'sidebar_top'    => '300/250',
        'sidebar_sticky' => '300/600',
        'footer_banner'  => '970/90',
    );
    $img_aspect = $aspect ?: ($aspect_map[$slot] ?? '4/1');
    $img = '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? '') . '" loading="lazy" style="aspect-ratio:' . esc_attr($img_aspect) . ';width:100%;height:auto;object-fit:cover;display:block;" />';
    echo '<div class="ad-slot" data-slot="' . esc_attr($slot) . '">';
    echo $link ? '<a href="' . esc_url($link) . '" rel="nofollow sponsored noopener">' . $img . '</a>' : $img;
    echo '</div>';
    return;
}

// Placeholder fallback — share styling with na_ad_slot() helper.
if ($slot === 'custom') {
    $aspect = $aspect ?: '4/1';
    echo '<div class="ad-slot ad-slot--custom" data-slot="custom"><div class="ph" style="aspect-ratio: ' . esc_attr($aspect) . ';" data-label="' . esc_attr($label ?: 'Advertisement') . '"></div></div>';
    return;
}
na_ad_slot($slot);
