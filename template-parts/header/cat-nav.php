<?php
/**
 * Sticky category nav. Home tile, nav links, tool buttons.
 *
 * Nav source: Appearance → Menus "Primary Category Nav" when assigned,
 * otherwise the hardcoded na_get_categories() list. Active state driven
 * by $GLOBALS['na_active_cat'] set by page templates before get_header().
 *
 * On mobile, the inline <ul> is hidden by CSS and a hamburger button
 * opens a side drawer containing the same items.
 */
if (! defined('ABSPATH')) exit;

$active     = na_active_cat();
$categories = na_get_categories();
?>
<nav class="cat-nav" aria-label="<?php esc_attr_e('Primary', 'rastriya-aawaj'); ?>">
    <div class="container-wide inner">
        <button type="button" class="cat-nav__burger" id="ra-burger" aria-label="<?php esc_attr_e('Open menu', 'rastriya-aawaj'); ?>" aria-expanded="false" aria-controls="ra-mobile-drawer">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="home" aria-label="<?php esc_attr_e('Home', 'rastriya-aawaj'); ?>"><?php echo na_icon('home'); ?></a>
        <?php if (has_nav_menu('primary')):
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'cat-nav__list',
                'menu_id'        => '',
                'items_wrap'     => '<ul class="cat-nav__list">%3$s</ul>',
                'depth'          => 1,
                'fallback_cb'    => false,
            ));
        else: ?>
            <ul class="cat-nav__list">
                <?php foreach ($categories as $cat) :
                    $is_active = $active === $cat['slug'] ? ' class="active"' : '';
                    $href      = esc_url(na_cat_link($cat['slug'])); ?>
                    <li><a href="<?php echo $href; ?>"<?php echo $is_active; ?>><?php echo esc_html($cat['np']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="tools">
            <button type="button" id="ra-search-btn" aria-label="<?php esc_attr_e('Search', 'rastriya-aawaj'); ?>" aria-expanded="false" aria-controls="ra-search-drawer"><?php echo na_icon('search'); ?></button>
            <button type="button" id="ra-dark-btn" aria-label="<?php esc_attr_e('Toggle dark mode', 'rastriya-aawaj'); ?>" aria-pressed="false"><?php echo na_icon('moon'); ?></button>
            <button type="button" id="ra-notif-btn" aria-label="<?php esc_attr_e('Notifications', 'rastriya-aawaj'); ?>"><?php echo na_icon('bell'); ?></button>
        </div>
    </div>
</nav>

<!-- Mobile drawer (hidden by default; opened by the burger above). -->
<div class="mobile-drawer" id="ra-mobile-drawer" hidden aria-hidden="true">
    <div class="mobile-drawer__backdrop" data-drawer-close></div>
    <aside class="mobile-drawer__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Site navigation', 'rastriya-aawaj'); ?>">
        <header class="mobile-drawer__head">
            <span class="mobile-drawer__title"><?php esc_html_e('मेनु', 'rastriya-aawaj'); ?></span>
            <button type="button" class="mobile-drawer__close" data-drawer-close aria-label="<?php esc_attr_e('Close menu', 'rastriya-aawaj'); ?>">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </header>
        <nav aria-label="<?php esc_attr_e('Mobile primary', 'rastriya-aawaj'); ?>">
            <?php if (has_nav_menu('primary')):
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => '',
                    'menu_id'        => '',
                    'items_wrap'     => '<ul class="mobile-drawer__list">%3$s</ul>',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ));
            else: ?>
                <ul class="mobile-drawer__list">
                    <?php foreach ($categories as $cat):
                        $is_active = $active === $cat['slug'] ? ' class="active"' : '';
                        $href      = esc_url(na_cat_link($cat['slug'])); ?>
                        <li><a href="<?php echo $href; ?>"<?php echo $is_active; ?>><?php echo esc_html($cat['np']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </nav>
    </aside>
</div>

<script>
(function () {
    var burger = document.getElementById('ra-burger');
    var drawer = document.getElementById('ra-mobile-drawer');
    if (!burger || !drawer) return;
    var closers = drawer.querySelectorAll('[data-drawer-close]');

    function openDrawer() {
        drawer.hidden = false;
        drawer.setAttribute('aria-hidden', 'false');
        burger.setAttribute('aria-expanded', 'true');
        document.body.classList.add('mobile-drawer-open');
    }
    function closeDrawer() {
        drawer.hidden = true;
        drawer.setAttribute('aria-hidden', 'true');
        burger.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('mobile-drawer-open');
        burger.focus();
    }
    burger.addEventListener('click', openDrawer);
    closers.forEach(function (el) { el.addEventListener('click', closeDrawer); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !drawer.hidden) closeDrawer();
    });
}());
</script>
