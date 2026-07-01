<?php
/**
 * Front page entry point.
 *
 * Renders the assigned static front page's block layout. Editors control
 * the homepage by editing that page in Gutenberg.
 */
if (! defined('ABSPATH')) exit;

$GLOBALS['na_active_cat'] = 'home';
get_header();

$front = (int) get_option('page_on_front');
if ($front) {
    $post = get_post($front);
    if ($post) {
        setup_postdata($post);
        echo apply_filters('the_content', $post->post_content);
        wp_reset_postdata();
    }
}

get_footer();
