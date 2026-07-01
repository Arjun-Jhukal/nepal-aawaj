<?php
/**
 * Custom post types + taxonomies the theme assumes exist.
 *
 * - candidate: single-candidate.php
 * - party:     single-party.php
 * - Both share a `location` taxonomy and use ACF for structured fields
 *   (candidate_current_party, candidate_career, etc).
 */
if (! defined('ABSPATH')) exit;

function na_register_cpts() {
    register_post_type('candidate', array(
        'labels' => array(
            'name'          => __('Candidates', 'rastriya-aawaj'),
            'singular_name' => __('Candidate',  'rastriya-aawaj'),
            'add_new_item'  => __('Add New Candidate', 'rastriya-aawaj'),
            'edit_item'     => __('Edit Candidate',    'rastriya-aawaj'),
            'menu_name'     => __('Candidates', 'rastriya-aawaj'),
        ),
        'public'        => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-businessperson',
        'menu_position' => 22,
        'has_archive'   => 'candidates',
        'rewrite'       => array('slug' => 'candidate'),
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'    => array('candidate_location'),
    ));

    register_post_type('party', array(
        'labels' => array(
            'name'          => __('Parties', 'rastriya-aawaj'),
            'singular_name' => __('Party',   'rastriya-aawaj'),
            'add_new_item'  => __('Add New Party', 'rastriya-aawaj'),
            'edit_item'     => __('Edit Party',    'rastriya-aawaj'),
            'menu_name'     => __('Parties', 'rastriya-aawaj'),
        ),
        'public'        => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-flag',
        'menu_position' => 23,
        'has_archive'   => 'parties',
        'rewrite'       => array('slug' => 'party'),
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    ));

    register_taxonomy('candidate_location', array('candidate'), array(
        'labels' => array(
            'name'          => __('Locations', 'rastriya-aawaj'),
            'singular_name' => __('Location',  'rastriya-aawaj'),
        ),
        'public'       => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => array('slug' => 'location'),
    ));
}
add_action('init', 'na_register_cpts', 5);
