<?php
/**
 * Single article — orchestrates detail section partials.
 * Visual port of design_handoff_rastriya_aawaj/reference/detail.html.
 * Sections live under template-parts/single/.
 */
if (! defined('ABSPATH')) exit;

$GLOBALS['na_active_cat'] = 'politics';
get_header();

get_template_part('template-parts/single/read-progress');
?>

<div class="container-wide article-wrap">
    <div class="article-grid">
        <?php
        get_template_part('template-parts/single/article-rail');
        get_template_part('template-parts/single/story');
        get_template_part('template-parts/single/article-aside');
        ?>
    </div>
</div>

<?php
get_template_part('template-parts/single/share-modal');
get_template_part('template-parts/single/scripts');
get_footer();
