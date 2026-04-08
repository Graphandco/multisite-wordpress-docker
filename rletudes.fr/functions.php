<?php
/*********************
	 LOGIN PAGE
**********************/

function custom_login_styles() {
    wp_enqueue_style(
        'custom-login',
        get_stylesheet_directory_uri() . '/custom/login.css',
        [],
        '1.0'
    );
}
add_action('login_enqueue_scripts', 'custom_login_styles');

add_filter('login_headerurl', function () {
    return home_url();
});

add_filter('login_headertext', function () {
    return get_bloginfo('name');
});