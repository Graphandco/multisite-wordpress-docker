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

// HIDE ADMIN MENUS FOR EDITORS
function gc_hide_admin_menus_for_editors() {
    if (current_user_can('editor') && !current_user_can('administrator')) {
        remove_menu_page('tools.php'); // Outils
        remove_menu_page('wpseo_dashboard'); // Yoast SEO
    }
}
add_action('admin_menu', 'gc_hide_admin_menus_for_editors', 999);