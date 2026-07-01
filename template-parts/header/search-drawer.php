<?php
/**
 * Inline search drawer. Hidden by default; shared.js toggles display when
 * the user hits the search tool in cat-nav. Posts to the WP search endpoint.
 */
if (! defined('ABSPATH')) exit;

$placeholder = na_option('ts_search_placeholder', 'समाचार, विषय वा कुनै पनि शब्द खोज्नुहोस्...');
$button      = na_option('ts_search_button',      'खोज');
?>
<div id="ra-search-drawer" style="display:none; background:#fff; border-bottom:1px solid var(--gray-2); padding: 20px 0;">
    <div class="container">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="display:flex; gap:8px; max-width:720px; margin:0 auto;">
            <input type="search" name="s" placeholder="<?php echo esc_attr($placeholder); ?>" class="field" style="flex:1;" autofocus>
            <button class="btn btn-red" type="submit"><?php echo na_icon('search'); ?><span><?php echo esc_html($button); ?></span></button>
        </form>
    </div>
</div>
