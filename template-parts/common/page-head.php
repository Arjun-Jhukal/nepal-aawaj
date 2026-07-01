<?php
/**
 * Page head — breadcrumbs + accent-bar title + optional English sub-label + optional lede paragraph.
 * Used by listing/archive, contact, privacy, terms, search.
 *
 * Pass via get_template_part()'s third arg (WP 5.5+):
 *
 *   get_template_part('template-parts/common/page-head', null, array(
 *       'crumbs' => array(
 *           array('label' => 'गृहपृष्ठ', 'href' => home_url('/')),
 *           array('label' => 'कानुनी'),
 *           array('label' => 'गोपनीयता नीति'), // last entry = current page; no href
 *       ),
 *       'title'  => 'गोपनीयता नीति',
 *       'en_sub' => 'Privacy Policy',
 *       'sub'    => 'तपाईंको गोपनीयता हाम्रो...',
 *   ));
 */
if (! defined('ABSPATH')) exit;

$crumbs = isset($args['crumbs']) ? (array) $args['crumbs'] : array();
$title  = isset($args['title'])  ? (string) $args['title']  : '';
$en_sub = isset($args['en_sub']) ? (string) $args['en_sub'] : '';
$sub    = isset($args['sub'])    ? (string) $args['sub']    : '';
$count  = count($crumbs);
?>
<div class="page-head">
    <div class="container-wide">
        <?php if ($crumbs) : ?>
        <div class="crumbs">
            <?php foreach ($crumbs as $i => $c) :
                $is_last = ($i === $count - 1);
                if ($i > 0) echo '<span class="sep">›</span> ';
                if (! $is_last && ! empty($c['href'])) {
                    echo '<a href="' . esc_url($c['href']) . '">' . esc_html($c['label']) . '</a>';
                } else {
                    echo '<span style="color: var(--ink);">' . esc_html($c['label']) . '</span>';
                }
            endforeach; ?>
        </div>
        <?php endif; ?>

        <h1>
            <span class="accent-bar"></span><?php echo esc_html($title); ?>
            <?php if ($en_sub) : ?>
                <span style="font-family: var(--f-ui); font-size: 14px; color: var(--red); letter-spacing: 0.16em; text-transform: uppercase; font-weight: 600;"><?php echo esc_html($en_sub); ?></span>
            <?php endif; ?>
        </h1>

        <?php if ($sub) : ?>
            <p class="sub"><?php echo esc_html($sub); ?></p>
        <?php endif; ?>
    </div>
</div>
