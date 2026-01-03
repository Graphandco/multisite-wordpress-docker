<?php

/******************************************************************************
    CHILDREN PAGES QUERY ELEMENTOR : query ID -> child_pages
******************************************************************************/

// Showing children of current page in Posts Widget
add_action( 'elementor/query/child_pages', function( $query ) {
    // Get current post tags
    $current_page = get_queried_object_id();
    // Modify the query
    $query->set( 'post_parent', $current_page );
} );


/******************************************************************************
    AJOUT DES FICHIERS CSS ET JS
******************************************************************************/
function meo_scripts_and_styles() {
    if( !is_admin() ) {
        wp_deregister_script('jquery');
        wp_register_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', false, '');
        wp_enqueue_script('jquery');
        // register main stylesheet
        if (file_exists(get_stylesheet_directory() . '/styles/global.css')) {
           wp_enqueue_style( 'meo-main-child-stylesheet', get_stylesheet_directory_uri() . '/styles/global.css' );
        }
        wp_enqueue_style( 'slick', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css' );
        wp_enqueue_style( 'slick-theme', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css' );
        // register form stylesheet
        if (file_exists(get_stylesheet_directory() . '/styles/form.css')) {
           wp_enqueue_style( 'meo-form-child-stylesheet', get_stylesheet_directory_uri() . '/styles/form.css' );
        }
        // register custom stylesheet
        if (file_exists(get_stylesheet_directory() . '/styles/custom.css')) {
           wp_enqueue_style( 'meo-custom-child-stylesheet', get_stylesheet_directory_uri() . '/styles/custom.css' );
        }        
        // scripts file in the footer
        wp_enqueue_script( 'slick', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js' );
        if (file_exists(get_stylesheet_directory() . '/scripts/gsap/gsap.min.js')) {
           wp_enqueue_script( 'gsap-core', get_stylesheet_directory_uri() . '/scripts/gsap/gsap.min.js' );
        }
        if (file_exists(get_stylesheet_directory() . '/scripts/gsap/scrolltrigger.min.js')) {
           wp_enqueue_script( 'gsap-scrolltrigger', get_stylesheet_directory_uri() . '/scripts/gsap/scrolltrigger.min.js' );
        }
		wp_enqueue_script( 'lenis', '//cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js' );
        if (file_exists(get_stylesheet_directory() . '/scripts/main.js')) {
           wp_enqueue_script( 'meo-child-theme-js', get_stylesheet_directory_uri() . '/scripts/main.js' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'meo_scripts_and_styles', 999 );



/******************************************************************************
    AJOUT DU .HTML DANS LES PERMALIENS POUR OPIMISER LE SEO
******************************************************************************/
add_action('init', 'html_page_permalink', -1);
register_activation_hook(__FILE__, 'active');
register_deactivation_hook(__FILE__, 'deactive');


function html_page_permalink() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
	}
}
add_filter('user_trailingslashit', 'no_page_slash',66,2);
function no_page_slash($string, $type){
	global $wp_rewrite;
	if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page'){
		return untrailingslashit($string);
	}else{
		return $string;
	}
}

function active() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
 	}
  	$wp_rewrite->flush_rules();
}	
function deactive() {
	global $wp_rewrite;
	$wp_rewrite->page_structure = str_replace(".html","",$wp_rewrite->page_structure);
	$wp_rewrite->flush_rules();
}

/******************************************************************************
    AUTRES AJOUTS
******************************************************************************/

// Désactivation de Gutemberg
add_filter('use_block_editor_for_post', '__return_false', 10);

// Placer le block yoast en bas des pages du backoffice
function yoasttobottom() {
    return 'low';
}

// Scroll automatique vers le formulaire après l'envoi
add_filter( 'gform_confirmation_anchor', '__return_true' );

// Supprimer la barre d'administration sur le front
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	show_admin_bar(false);
}
