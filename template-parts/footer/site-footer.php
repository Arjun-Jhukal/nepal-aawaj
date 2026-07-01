<?php
/**
 * Site footer — brand column + two category columns + contact / newsletter
 * column, with the bottom row (copyright + meta links).
 */
if (! defined('ABSPATH')) exit;

$categories  = na_get_categories();
$cat_col_1   = array_slice($categories, 0, 6);
$cat_col_2   = array_slice($categories, 6);

$ts_logo     = na_image_data(na_option('ts_logo'));
$ts_name     = na_option('ts_site_name',        get_bloginfo('name') ?: 'RASTRIYA AAWAJ');
$ts_desc     = na_option('ts_site_description', 'राष्ट्रिय आवाज एक स्वतन्त्र डिजिटल समाचार पोर्टल हो, जसले सत्य, निष्पक्ष र विश्वसनीय समाचार र विश्लेषण प्रस्तुत गर्दछ।');
$ts_copy     = na_option('ts_copyright',        'Rastriya Aawaj Media Pvt. Ltd. · सर्वाधिकार सुरक्षित');
$ts_address  = na_option('ts_address',          'नयाँ बानेश्वर, काठमाडौं, नेपाल');
$ts_phone    = na_option('ts_phone',            '+977 1 4123456');
$ts_email    = na_option('ts_email',            'news@rastriyaaawaj.com');

$ts_fb = na_option('ts_facebook_url');
$ts_tw = na_option('ts_twitter_url');
$ts_yt = na_option('ts_youtube_url');
$ts_ig = na_option('ts_instagram_url');
$ts_wa = na_option('ts_whatsapp_url');

$ts_col1_heading      = na_option('ts_footer_col1_heading',      'विभाग');
$ts_col2_heading      = na_option('ts_footer_col2_heading',      'थप');
$ts_col3_heading      = na_option('ts_footer_col3_heading',      'सम्पर्क');
$ts_news_heading      = na_option('ts_newsletter_heading',       'न्युजलेटर');
$ts_news_blurb        = na_option('ts_newsletter_blurb',         'दैनिक मुख्य समाचार आफ्नो इमेलमा पाउनुहोस्।');
$ts_news_btn          = na_option('ts_newsletter_btn',           'सब्स्क्राइब');
$ts_news_placeholder  = na_option('ts_newsletter_placeholder',   'your@email.com');
$ts_bottom_links      = na_option('ts_footer_links', array(
    array('label' => 'Privacy', 'url' => home_url('/privacy/')),
    array('label' => 'Terms',   'url' => home_url('/terms/')),
    array('label' => 'Contact', 'url' => home_url('/contact/')),
    array('label' => 'About',   'url' => home_url('/about/')),
));
?>
<footer class="footer" role="contentinfo">
    <div class="container-wide">
        <div class="footer-grid">
            <!-- Brand column -->
            <div class="brand-col">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-brand" aria-label="<?php echo esc_attr($ts_name); ?>">
                    <span class="logo-mark footer-brand__mark">
                        <?php if ($ts_logo) : ?>
                            <img src="<?php echo esc_url($ts_logo['url']); ?>" alt="<?php echo esc_attr($ts_logo['alt'] ?: $ts_name); ?>" width="44" height="44">
                        <?php else : ?>
                            <?php echo na_brand_svg(); ?>
                        <?php endif; ?>
                    </span>
                    <span class="brand-name footer-brand__name"><?php echo esc_html($ts_name); ?></span>
                </a>
                <p><?php echo esc_html($ts_desc); ?></p>
                <div class="footer-socials">
                    <?php if ($ts_fb) : ?><a href="<?php echo esc_url($ts_fb); ?>" class="btn btn-ghost footer-social" aria-label="Facebook"  target="_blank" rel="noopener"><?php echo na_icon('fb');       ?></a><?php endif; ?>
                    <?php if ($ts_tw) : ?><a href="<?php echo esc_url($ts_tw); ?>" class="btn btn-ghost footer-social" aria-label="X/Twitter" target="_blank" rel="noopener"><?php echo na_icon('tw');       ?></a><?php endif; ?>
                    <?php if ($ts_yt) : ?><a href="<?php echo esc_url($ts_yt); ?>" class="btn btn-ghost footer-social" aria-label="YouTube"   target="_blank" rel="noopener"><?php echo na_icon('yt');       ?></a><?php endif; ?>
                    <?php if ($ts_ig) : ?><a href="<?php echo esc_url($ts_ig); ?>" class="btn btn-ghost footer-social" aria-label="Instagram" target="_blank" rel="noopener"><?php echo na_icon('ig');       ?></a><?php endif; ?>
                    <?php if ($ts_wa) : ?><a href="<?php echo esc_url($ts_wa); ?>" class="btn btn-ghost footer-social" aria-label="WhatsApp"  target="_blank" rel="noopener"><?php echo na_icon('whatsapp'); ?></a><?php endif; ?>
                </div>
            </div>

            <!-- Category column 1 -->
            <div>
                <h4><?php echo esc_html($ts_col1_heading); ?></h4>
                <?php if (has_nav_menu('footer_one')):
                    wp_nav_menu(array(
                        'theme_location' => 'footer_one',
                        'container'      => false,
                        'menu_class'     => '',
                        'menu_id'        => '',
                        'items_wrap'     => '<ul>%3$s</ul>',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ));
                else: ?>
                    <ul>
                        <?php foreach ($cat_col_1 as $cat) : ?>
                            <li><a href="<?php echo esc_url(na_cat_link($cat['slug'])); ?>"><?php echo esc_html($cat['np']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Category column 2 -->
            <div>
                <h4><?php echo esc_html($ts_col2_heading); ?></h4>
                <?php if (has_nav_menu('footer_two')):
                    wp_nav_menu(array(
                        'theme_location' => 'footer_two',
                        'container'      => false,
                        'menu_class'     => '',
                        'menu_id'        => '',
                        'items_wrap'     => '<ul>%3$s</ul>',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ));
                else: ?>
                    <ul>
                        <?php foreach ($cat_col_2 as $cat) : ?>
                            <li><a href="<?php echo esc_url(na_cat_link($cat['slug'])); ?>"><?php echo esc_html($cat['np']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Contact + newsletter -->
            <div>
                <h4><?php echo esc_html($ts_col3_heading); ?></h4>
                <?php if ($ts_address) : ?><div class="contact-item"><?php echo na_icon('pin'); ?><span><?php echo esc_html($ts_address); ?></span></div><?php endif; ?>
                <?php if ($ts_phone)  : ?><div class="contact-item"><?php echo na_icon('phone'); ?><span class="en"><?php echo esc_html($ts_phone); ?></span></div><?php endif; ?>
                <?php if ($ts_email)  : ?><div class="contact-item"><?php echo na_icon('email'); ?><span class="en"><?php echo esc_html($ts_email); ?></span></div><?php endif; ?>

                <?php if ($ts_news_heading) : ?>
                <h4 class="footer-news__heading"><?php echo esc_html($ts_news_heading); ?></h4>
                <p class="footer-news__blurb"><?php echo esc_html($ts_news_blurb); ?></p>
                <form class="newsletter-mini" role="form" aria-label="<?php echo esc_attr($ts_news_heading); ?>">
                    <label class="screen-reader-text" for="footer-newsletter-email"><?php esc_html_e('Email address', 'rastriya-aawaj'); ?></label>
                    <input id="footer-newsletter-email" type="email" placeholder="<?php echo esc_attr($ts_news_placeholder); ?>" required>
                    <button type="submit"><?php echo esc_html($ts_news_btn); ?></button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-bottom">
            <div>© <?php echo esc_html(date_i18n('Y')); ?> <?php echo esc_html($ts_copy); ?></div>
            <div class="links">
                <?php if (has_nav_menu('footer_meta')):
                    wp_nav_menu(array(
                        'theme_location' => 'footer_meta',
                        'container'      => false,
                        'menu_class'     => '',
                        'menu_id'        => '',
                        'items_wrap'     => '%3$s',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ));
                else:
                    foreach ((array) $ts_bottom_links as $link):
                        $label = isset($link['label']) ? $link['label'] : '';
                        $url   = isset($link['url'])   ? $link['url']   : '#';
                        if ($label === '') continue;
                ?>
                    <a href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
                <?php
                    endforeach;
                endif; ?>
            </div>
        </div>
    </div>
</footer>
