<?php
/** Search hero — breadcrumb + big input + popular tag chips.
 *  $args['q'] = current query (defaults to 'बजेट' so static page has visible content). */
if (! defined('ABSPATH')) exit;

$q = isset($args['q']) ? (string) $args['q'] : '';
?>
<section class="search-hero">
    <div class="container-wide">
        <div class="crumbs" style="font-family: var(--f-ui); font-size: 12px; color: var(--gray-6); letter-spacing: 0.04em;">
            <a href="<?php echo esc_url(home_url('/')); ?>" style="color: var(--gray-6);">गृहपृष्ठ</a>
            <span style="margin: 0 8px; color: var(--gray-4);">›</span>
            <span style="color:var(--ink);">खोज</span>
        </div>
        <h1 style="font-family: var(--f-display-dn); font-size: 36px; font-weight: 500; margin-top: 10px; display: flex; align-items: center; gap: 16px;">
            <span style="width: 6px; height: 32px; background: var(--red);"></span>
            समाचार खोज
            <span style="font-family: var(--f-ui); font-size: 13px; color: var(--red); letter-spacing: 0.16em; text-transform: uppercase; font-weight: 600;">Search</span>
        </h1>
        <form class="big-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" id="q" name="s" placeholder="समाचार, विषय, ठाउँ वा कुनै पनि शब्द खोज्नुहोस्..." value="<?php echo esc_attr($q); ?>">
            <button type="submit">
                <?php echo na_icon('search'); ?>
                खोज्नुहोस्
            </button>
        </form>
        <div class="quick-tags">
            <span class="lab">लोकप्रिय:</span>
            <a class="tag" href="?s=<?php echo urlencode('बजेट २०८३'); ?>"># बजेट २०८३</a>
            <a class="tag" href="?s=<?php echo urlencode('क्रिकेट'); ?>"># क्रिकेट</a>
            <a class="tag" href="?s=<?php echo urlencode('डेङ्गु'); ?>"># डेङ्गु</a>
            <a class="tag" href="?s=<?php echo urlencode('शेयर बजार'); ?>"># शेयर बजार</a>
            <a class="tag" href="?s=<?php echo urlencode('SEE नतिजा'); ?>"># SEE नतिजा</a>
            <a class="tag" href="?s=<?php echo urlencode('जलवायु'); ?>"># जलवायु</a>
        </div>
    </div>
</section>
