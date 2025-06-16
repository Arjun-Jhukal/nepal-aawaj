<?php

/** ENABLE TITLE TAGS THUMBNAIL AND MENUS FUNCTIONALITY OF THE WORDPRESS */

function arid_theme_support()
{
    add_theme_support('title-tag');
    add_theme_support('tags');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
}

add_action('after_setup_theme', 'arid_theme_support');


/** ENABLE UPLOAD OF THE SVG */
add_post_type_support('posts', 'excerpt');function cc_mime_types($mimes)
{
  $mimes['svg'] = 'image/svg+xml';
  $mimes['webp'] = 'image/webp';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

/** DISABLE CONTACT FORM FROM ADDING BR ETC  */
add_filter('wpcf7_autop_or_not', '__return_false');

/** DEFINE MENUS */
function register_menues(){
  register_nav_menu('primary-menu', __('Primary Menu','aridZone'));
  register_nav_menu('quick-link', __('Quick Links','aridZone'));
  register_nav_menu('services', __('Services','aridZone'));
  register_nav_menu('others', __('Other Links','aridZone'));
}

add_action('after_setup_theme', "register_menues");