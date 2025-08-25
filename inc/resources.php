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
  
    wp_enqueue_script("slick", "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js", array('jQuery'), '1.9.0', true); 
    wp_enqueue_script("gsap", "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.6/gsap.min.js", array('jQuery'), "3.12.6", true);
    wp_enqueue_script("scrollTrigger", "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.6/ScrollTrigger.min.js", array('jQuery'), "3.12.6", true);  
    wp_enqueue_script("animtion-js", get_template_directory_uri() . "/assets/js/animation.min.js", array('jQuery', 'gsap','scrollTrigger','slick'), '1.0.0', true);
    wp_enqueue_script("script", get_template_directory_uri() . "/assets/js/script.min.js", array('jQuery',  'slick'), '1.0.0', true);
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
        'ProximaNova-Regular.woff2',
        'ProximaNova-Semibold.woff2',
        'ProximaNova-Bold.woff2',
        'ProximaNova-ExtraBold.woff2',
        'ProximaNova-Black.woff2',
    ];

    foreach ($fonts as $font) {
        printf(
            '<link rel="preload" href="%s/assets/fonts/%s" as="font" type="font/woff2" crossorigin="anonymous">' . "\n",
            get_template_directory_uri(),
            $font
        );
    }

    // Preload Mona Sans (Google Fonts)
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">';
}
add_action('wp_head', 'preload_theme_fonts', 1);