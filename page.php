<?php
/**
 * Universal page template — every page is built from blocks and rendered
 * via the_content(). No per-page templates. Editors compose pages in
 * Gutenberg using the rastriya-aawaj block library.
 */
if (! defined('ABSPATH')) exit;

get_header();

if (have_posts()): the_post();
    the_content();
endif;

get_footer();
