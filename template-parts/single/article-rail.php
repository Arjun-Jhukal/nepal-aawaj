<?php
/**
 * Article share / utility rail. Rendered twice: at the top (sticky left rail
 * on desktop, horizontal strip on mobile) and at the end of the story body.
 *
 * Markup notes (multi-instance safe):
 *   - Per-button JS uses .js-* classes, not IDs (avoid duplicate IDs).
 *   - Buttons marked data-rail-overflow="1" collapse behind the "+N" toggle
 *     on narrow viewports; the script toggles `.is-open` on the rail.
 *   - Primary share row visible on mobile: like, Facebook, Twitter/X, WhatsApp.
 */
if (! defined('ABSPATH')) exit;

$permalink = esc_url(get_permalink());
$title_enc = rawurlencode(get_the_title());
$url_enc   = rawurlencode(get_permalink());

$share_fb = 'https://www.facebook.com/sharer/sharer.php?u=' . $url_enc;
$share_tw = 'https://twitter.com/intent/tweet?text=' . $title_enc . '&url=' . $url_enc;
$share_wa = 'https://wa.me/?text=' . $title_enc . '%20' . $url_enc;
?>
<div class="article-rail">
    <button class="rail-btn js-like" title="मन पर्‍यो" type="button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
        <span class="count js-like-count">0</span>
        <span class="tip">मन पर्‍यो</span>
    </button>
    <a class="rail-btn" href="<?php echo esc_url($share_fb); ?>" target="_blank" rel="noopener" title="फेसबुक" aria-label="Share on Facebook"><?php echo na_icon('fb'); ?><span class="tip">फेसबुक</span></a>
    <a class="rail-btn" href="<?php echo esc_url($share_tw); ?>" target="_blank" rel="noopener" title="ट्विटर / X" aria-label="Share on X"><?php echo na_icon('tw'); ?><span class="tip">X मा सेयर</span></a>
    <a class="rail-btn" href="<?php echo esc_url($share_wa); ?>" target="_blank" rel="noopener" title="WhatsApp" aria-label="Share on WhatsApp"><?php echo na_icon('whatsapp'); ?><span class="tip">WhatsApp</span></a>

    <button class="rail-btn rail-more" type="button" aria-expanded="false" aria-label="थप विकल्प">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
        <span class="rail-more__count">+4</span>
        <span class="tip">थप विकल्प</span>
    </button>

    <button class="rail-btn js-bookmark" data-rail-overflow="1" title="बुकमार्क" type="button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
        <span class="tip">सेभ गर्नुहोस्</span>
    </button>
    <button class="rail-btn js-copy" data-rail-overflow="1" data-permalink="<?php echo $permalink; ?>" title="लिङ्क कपी" type="button">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        <span class="tip">लिङ्क कपी</span>
    </button>
    <button class="rail-btn" data-rail-overflow="1" title="प्रिन्ट" type="button" onclick="window.print()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6,9 6,2 18,2 18,9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        <span class="tip">प्रिन्ट</span>
    </button>
    <button class="rail-btn js-font" data-rail-overflow="1" title="फन्ट साइज" type="button">
        <span style="font-family: var(--f-display-dn); font-size: 18px; font-weight: 600;">अ</span>
        <span style="font-family: var(--f-display-dn); font-size: 11px; font-weight: 600; margin-left: -2px;">अ</span>
        <span class="tip">फन्ट साइज</span>
    </button>
</div>
