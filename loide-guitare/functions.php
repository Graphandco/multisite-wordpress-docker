<?php

// @ini_set('display_errors', 'on');
// @error_reporting(E_ALL);

/**
** activation theme
**/
add_filter('login_display_language_dropdown', '__return_false');

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/custom/style.css', array('parent-style'));
}

function script_header() { ?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-154610382-3"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-154610382-3');
	</script>
<?php
 }
add_action( 'wp_head', "script_header" );

/**
 * Adding custom icon to icon control in Elementor
 */
 function jet_add_custom_icons_tab( $tabs = array() ) {

	// Append new icons
	$new_icons = array(
		'account',
		'airplane',
		'wordpress',
	);
	
	$tabs['my-custom-icons'] = array(
		'name'          => 'my-custom-icons',
		'label'         => esc_html__( 'Material Icons', 'text-domain' ),
		'labelIcon'     => 'fas fa-user',
		'prefix'        => 'mdi-',
		'displayPrefix' => 'mdi',
		'url'           => 'https://cdn.materialdesignicons.com/4.5.95/css/materialdesignicons.min.css',
		'icons'         => $new_icons,
		'ver'           => '1.0.0',
	);

	return $tabs;
}
add_filter( 'elementor/icons_manager/additional_tabs', 'jet_add_custom_icons_tab' );


/*************************************************
    GUITARE SINGLE CPT 
**************************************************/

/*QUERY DE LA GALERIE D'IMAGES*/


function get_single_cpt_gallery() {
    $images = get_field('photos_de_la_guitare'); 
    if($images):?>
        <div class="guitare-images">
        <?php foreach( $images as $image ): ?>
            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
        <?php endforeach;?>
        </div>
    <?php endif;
}
add_shortcode('guitare-gallery', 'get_single_cpt_gallery');

