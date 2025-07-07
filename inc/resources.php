<?php

function register_resources()
{
    // Enqueue styles
   
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css', array(), null);
    wp_enqueue_style('slick', "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css", array(), '1.9.0', 'all');
    // wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), null);
    wp_enqueue_style('utilites-css', get_template_directory_uri() . '/assets/css/utilities.css', array(), null);
    wp_enqueue_style('critical-css', get_template_directory_uri() . '/assets/css/critical.css', array(), null);
    wp_enqueue_style('styles-css', get_template_directory_uri() . '/assets/css/styles.css', array(), null);

    // Enqueue scripts
    wp_enqueue_script("jQuery", "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js", array(), "3.7.1", true);
    wp_enqueue_script("aos-animate", "https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js", array('jQuery'), "2.3.4", true); 
    wp_enqueue_script("slick", "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js", array('jQuery'), '1.9.0', true); 
    wp_enqueue_script("gsap", "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.6/gsap.min.js", array('jQuery'), "3.12.6", true);
    wp_enqueue_script("scrollTrigger", "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.6/ScrollTrigger.min.js", array('jQuery'), "3.12.6", true);  
    wp_enqueue_script("animtion-js", get_template_directory_uri() . "/assets/js/animation.min.js", array('jQuery', 'gsap','scrollTrigger','slick'), '1.0.0', true);
    wp_enqueue_script("script", get_template_directory_uri() . "/assets/js/script.min.js", array('jQuery', 'aos-animate', 'slick'), '1.0.0', true);
    wp_enqueue_script("makura", get_template_directory_uri() . "/assets/js/makura.min.js", array('jQuery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'register_resources');

function dashboard_resources()
{
    wp_enqueue_style('dashboard-styles-css', get_template_directory_uri() . '/assets/css/dashboard-style.css', array(), null);
}

add_action('admin_enqueue_scripts', 'dashboard_resources');

function preload_theme_fonts() {
    $fonts = [
        'HelveticaNowText-Regular.woff2',
        'HelveticaNowText-Medium.woff2',
        'HelveticaNowText-Bold.woff2',
        'HelveticaNowText-ExtraBold.woff2',
        'RoobertPRO-Light.woff2',
        'RoobertPRO-Regular.woff2',
        'RoobertPRO-Medium.woff2',
        'RoobertPRO-Bold.woff2',
    ];

    foreach ($fonts as $font) {
        printf(
            '<link rel="preload" href="%s/assets/fonts/%s" as="font" type="font/woff2" crossorigin="anonymous">' . "\n",
            get_template_directory_uri(),
            $font
        );
    }
}
add_action('wp_head', 'preload_theme_fonts', 1);