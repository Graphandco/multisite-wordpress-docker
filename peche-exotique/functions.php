<?php

// @ini_set('display_errors', 'on');
// @error_reporting(E_ALL);

/*********************
	 LOGIN PAGE
**********************/

function peche_login_styles() {
    wp_enqueue_style(
        'peche-login',
        get_stylesheet_directory_uri() . '/custom/login.css',
        [],
        '1.0'
    );
}
add_action('login_enqueue_scripts', 'peche_login_styles');

add_filter('login_headerurl', function () {
    return home_url();
});

add_filter('login_headertext', function () {
    return get_bloginfo('name');
});

/*********************
	 LOGIN REWRITE
**********************/

function gc_login_slug() {
    return 'connexion';
}

/*
 * Rediriger wp-login.php
 */
function gc_hide_wp_login() {

    $login_slug = gc_login_slug();
    $request_uri = $_SERVER['REQUEST_URI'];

    // accès direct à wp-login.php
    if (strpos($request_uri, 'wp-login.php') !== false) {

        // autoriser certaines actions internes
        if (isset($_GET['action']) && in_array($_GET['action'], [
            'logout',
            'lostpassword',
            'rp',
            'resetpass'
        ])) {
            return;
        }

        wp_redirect(home_url('/' . $login_slug . '/'));
        exit;
    }

}

add_action('init', 'gc_hide_wp_login');


/*
 * Route /connexion
 */
function gc_login_rewrite() {

    add_rewrite_rule(
        '^' . gc_login_slug() . '/?$',
        'wp-login.php',
        'top'
    );

}

add_action('init', 'gc_login_rewrite');


/*
 * Modifier les liens login
 */
add_filter('login_url', function($login_url) {

    return home_url('/' . gc_login_slug() . '/');

});


/*
 * Flush automatique une fois
 */
function gc_flush_rewrite_once() {

    if (get_site_option('gc_login_flush') !== 'done') {

        flush_rewrite_rules();
        update_site_option('gc_login_flush', 'done');

    }

}

add_action('init', 'gc_flush_rewrite_once');


/*********************
	 ACTIVATION THEME
**********************/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/custom/style.css', array('parent-style'));
}

function wpb_adding_scripts() {
    wp_register_script('my_script', get_stylesheet_directory_uri() . '/custom/main.js', array('jquery'),'1.1', true);
    wp_enqueue_script('my_script');
} 
add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts', 999 ); 


function script_header() { ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-8NCQ1W8EFR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-8NCQ1W8EFR');
</script>
    <!-- Matomo -->
    <script>
    var _paq = window._paq = window._paq || [];
    /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u="https://stats.graphandco.com/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '7']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
    })();
    </script>
    <!-- End Matomo Code -->
    <?php
     }
add_action( 'wp_head', "script_header" );

/*********************
	 IMAGES SIZES
**********************/

add_image_size( 'subpages', 800, 600, true );

/*********************
	 SUBPAGES
**********************/

function getsubpages($atts) { 
    $atts = shortcode_atts(array(
        'page' => false,
        'remove' => '',
        'description' => true,
        'words' => 60,
        'exclude' => false,
        'number' => false,
    ), $atts);
 
    global $post;
    $page = ($atts['page'] !== false) ? $atts['page'] : $post->ID;
 
    /* Excluded posts */
    $exclude = ($atts['exclude'] != false) ? $exclude = explode(',', $atts['exclude']) : array();
 
    $args = array(
        'post_parent' => $page,
        'post_type' => 'page',
        'post__not_in' => $exclude
    );
 
    /* Limit number of posts? (for pagination purposes) */
    $args['posts_per_page'] = ($atts['number'] !== false) ? $atts['number'] : '-1';
 
    $subpages = new WP_query($args);
     
    /* Build list of pages */
    if ($subpages->have_posts()) : ?>
 
        <div class="subpages">
            <?php while ($subpages->have_posts()) : $subpages->the_post(); ?> 
				<div class="subpages-item">
					<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');?>
					<div class="subpages-image" style="background-image:url('<?php echo $large_image_url[0]; ?>');">
					</div>
					<div class="subpages-content">
						<h2><?php the_title(); ?></h2>
						<button><a href="<?php the_permalink() ?>" title="Voir le type de pêche : <?php the_title(); ?>">Découvrir</a></button>
					</div>
				</div>   
            <?php endwhile ?>
        </div>
        <?php 
       /* Reset query */
       wp_reset_postdata();
 
    else :
  
    endif;
 
 return $output;
}
add_shortcode('generate-subpages', 'getsubpages');

/*********************
	 POSTS QUERY
**********************/

if( !function_exists( 'function_shortcode_actus' ) ):
	function function_shortcode_actus($atts) {?>
		<?php ob_start();
		query_posts(
				array(
					'post_type' => $atts['type'],
					'category_name' => $atts['categorie-name'],
					'showposts' => $atts['showposts'],
			  )
			);
			if (have_posts()): ?>

			<div class="subpages">
	
				  <?php $i=2; ?>
				  <?php while( have_posts() ): the_post(); ?>

				  <div class="subpages-item" >
				  	<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');?>
					  <div class="subpages-image" style="background-image:url('<?php echo $large_image_url[0]; ?>');">
					  </div>
					  <div class="subpages-content">
						  <h2><?php the_title(); ?></h2>
						  <button><a href="<?php the_permalink() ?>" title="Voir le type de pêche : <?php the_title(); ?>">Lire la suite</a></button>
					  </div>
				  </div>

					
	
					<?php $i++; ?>
				  <?php endwhile; ?>
			</div>
			<?php endif;
			wp_reset_query();
		$content = ob_get_clean();
	  return $content; ?>
		<?php return;
	}
	endif;
	add_shortcode('actus', 'function_shortcode_actus');

/*
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
*/