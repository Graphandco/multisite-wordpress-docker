<?php
// Initialisation du Theme
add_action('after_setup_theme','graphandco_theme_init', 16);

add_filter('show_admin_bar', '__return_false');

if( !function_exists( 'graphandco_theme_init' ) ):
function graphandco_theme_init() {

    // enqueue base scripts and styles
    add_action( 'wp_enqueue_scripts', 'graphandco_scripts_add_styles', 999 );

    // launching this stuff after theme setup

    add_filter( 'wpseo_metabox_prio', 'yoasttobottom');
}
endif;

if( !function_exists( 'graphandco_scripts_add_styles' ) ):
function graphandco_scripts_add_styles() {
    if( !is_admin() ) {
        wp_deregister_script('jquery');
        wp_register_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', false, '');
        wp_enqueue_script('jquery');
        // register main stylesheet
        if (file_exists(get_stylesheet_directory() . '/custom/css/style.css')) {
           wp_enqueue_style( 'graphandco-stylesheet', get_stylesheet_directory_uri() . '/custom/css/style.css' );
        }

        // scripts file in the footer
        if (file_exists(get_stylesheet_directory() . '/custom/js/scripts.js')) {
           wp_enqueue_script( 'graphandco-js', get_stylesheet_directory_uri() . '/custom/js/scripts.js' );
        }
    }
}
endif;




