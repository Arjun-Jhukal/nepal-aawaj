<?php
/**
 * Category / archive listing — orchestrates archive section partials.
 * Visual port of design_handoff_rastriya_aawaj/reference/listing.html.
 * Sections live under template-parts/archive/. Page head reused from common/.
 */
if (! defined('ABSPATH')) exit;

$queried = get_queried_object();
$title   = '';
$en_sub  = '';
$sub     = '';
$crumbs  = array(
    array('label' => 'गृहपृष्ठ', 'href' => home_url('/')),
);

if (is_category() || is_tag() || is_tax()) {
    $title  = $queried->name;
    $en_sub = $queried->slug;
    $sub    = $queried->description;

    if ($queried->parent) {
        $parent = get_term($queried->parent, $queried->taxonomy);
        if ($parent && ! is_wp_error($parent)) {
            $crumbs[] = array('label' => $parent->name, 'href' => get_term_link($parent));
        }
    }
    $crumbs[] = array('label' => $title);
    $GLOBALS['na_active_cat'] = $queried->slug;
} elseif (is_author()) {
    $title    = get_the_author_meta('display_name', (int) $queried->ID);
    $en_sub   = 'Author';
    $sub      = get_the_author_meta('description', (int) $queried->ID);
    $crumbs[] = array('label' => 'लेखकहरू');
    $crumbs[] = array('label' => $title);
} elseif (is_date()) {
    $title    = wp_get_document_title();
    $en_sub   = 'Archive';
    $crumbs[] = array('label' => 'पुरालेख');
    $crumbs[] = array('label' => $title);
} else {
    $title    = post_type_archive_title('', false) ?: __('Archive', 'rastriya-aawaj');
    $crumbs[] = array('label' => $title);
}

get_header();

get_template_part('template-parts/common/page-head', null, array(
    'crumbs' => $crumbs,
    'title'  => $title,
    'en_sub' => $en_sub,
    'sub'    => $sub,
));
?>

<div class="container-wide listing-wrap">
    <div class="listing-grid">
        <div>
            <?php
            get_template_part('template-parts/archive/feature-story');
            get_template_part('template-parts/archive/filter-bar');
            get_template_part('template-parts/archive/listing-items');
            get_template_part('template-parts/archive/pagination');
            ?>
        </div>

        <?php get_template_part('template-parts/archive/archive-sidebar'); ?>
    </div>
</div>

<?php
get_template_part('template-parts/archive/scripts');
get_footer();
