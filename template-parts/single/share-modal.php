<?php
/**
 * Bottom-sheet modal for the article rail's "+N more" overflow.
 *
 * Rendered once per single-post view. Both the top rail and the bottom
 * share-strip rail open this same modal via .rail-more clicks. Buttons
 * inside the modal share .js-bookmark / .js-copy / .js-font classes with
 * the rail buttons, so the existing handlers in single/scripts.php fire
 * on both (state stays in sync; no duplicate logic).
 */
if (! defined('ABSPATH')) exit;

$permalink = esc_url(get_permalink());
?>
<div class="rail-modal" id="rail-modal" hidden aria-hidden="true">
    <div class="rail-modal__backdrop" data-rail-modal-close></div>
    <div class="rail-modal__sheet" role="dialog" aria-modal="true" aria-labelledby="rail-modal-title">
        <header class="rail-modal__head">
            <h2 id="rail-modal-title" class="rail-modal__title"><?php esc_html_e('थप विकल्प', 'rastriya-aawaj'); ?></h2>
            <button type="button" class="rail-modal__close" data-rail-modal-close aria-label="<?php esc_attr_e('Close', 'rastriya-aawaj'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </header>
        <div class="rail-modal__actions">
            <button type="button" class="rail-modal__action js-bookmark">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                <span class="rail-modal__label"><?php esc_html_e('सेभ गर्नुहोस्', 'rastriya-aawaj'); ?></span>
            </button>
            <button type="button" class="rail-modal__action js-copy" data-permalink="<?php echo $permalink; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                <span class="rail-modal__label"><?php esc_html_e('लिङ्क कपी', 'rastriya-aawaj'); ?></span>
            </button>
            <button type="button" class="rail-modal__action" onclick="window.print()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6,9 6,2 18,2 18,9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                <span class="rail-modal__label"><?php esc_html_e('प्रिन्ट', 'rastriya-aawaj'); ?></span>
            </button>
            <button type="button" class="rail-modal__action js-font">
                <span style="font-family: var(--f-display-dn); font-size: 16px; font-weight: 600;">अ अ</span>
                <span class="rail-modal__label"><?php esc_html_e('फन्ट साइज', 'rastriya-aawaj'); ?></span>
            </button>
        </div>
    </div>
</div>
