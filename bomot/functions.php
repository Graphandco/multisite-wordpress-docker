<?php
// @ini_set('display_errors', 'on');
// @error_reporting(E_ALL);

/*add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}*/
add_filter('login_display_language_dropdown', '__return_false');

function script_header() { ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1RS21EJ75Z"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-1RS21EJ75Z');
    </script>
    <?php
     }
add_action( 'wp_head', "script_header" );

/***********************
COMMENTAIRES
***********************/
if( !function_exists( 'commentaires' ) ):
    function commentaires() {
        ob_start();

        $args = array( 
            'status'  => 1,
            'post_type' => array( 'post', 'page' ), 
        );          
        // The Query          
        $comments_query = new WP_Comment_Query( $args ); 
        $comments = $comments_query->comments;
         
        // Comment Loop          
        if ( $comments ) { ?>
        <div class="comments-list">
            <?php foreach ( $comments as $comment ) { ?>
                <div class="home-comment">
                    <div class="home-comment-author"><?php echo $comment->comment_author;?></div>
                    <div class="home-comment-content"><?php echo $comment->comment_content;?></div>
                </div>
            <?php } ?>
            </div>
        <?php } else {
            echo 'Aucun commentaire à afficher.';
        }

        return ob_get_clean();
    }
    endif;
    add_shortcode('commentaires', 'commentaires');

/***********************
    RÉALISATIONS
***********************/
function getRealisations($atts) {
    ob_start();
    $args = array(
        'post_type' => 'realisation',
    );
    $loop = new WP_Query( $args ); ?>
        <div class="rea-wrapper">       
            <div class="rea-list">
                <?php while ( $loop->have_posts() ) : $loop->the_post();
                    $objectifs = get_field('objectifs');
                    ?>
                    <div class="rea-item">
                        <div class="rea-image"><?php the_post_thumbnail( 'large' ); ?></div>
                        <div class="rea-content">                       
                            <h2 class="rea-title"><?php the_title(); ?></h2>
                            <div class="rea-subtitle"><span>Missions : </span><?php the_content(); ?></div>
                            <div class="rea-objectif"><span>Objectifs : </span><?php echo $objectifs ?></div>
                        </div>
                    </div> 
                <?php endwhile;?>
            </div>      
            <div class="rea-controls">


            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="rea-prev" x="0px" y="0px" width="51.388px" height="51.388px" viewBox="0 0 51.388 51.388" style="enable-background:new 0 0 51.388 51.388;" xml:space="preserve">
                <g>
                    <g>
                        <path d="M9.169,51.388c-0.351,0-0.701-0.157-0.93-0.463c-0.388-0.514-0.288-1.243,0.227-1.634l31.066-23.598L8.461,2.098    C7.95,1.708,7.85,0.977,8.237,0.463c0.395-0.517,1.126-0.615,1.64-0.225l33.51,25.456L9.877,51.151    C9.664,51.31,9.415,51.388,9.169,51.388z"/>
                    </g>
                </g>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="rea-next" x="0px" y="0px" width="51.388px" height="51.388px" viewBox="0 0 51.388 51.388" style="enable-background:new 0 0 51.388 51.388;" xml:space="preserve">
                <g>
                    <g>
                        <path d="M9.169,51.388c-0.351,0-0.701-0.157-0.93-0.463c-0.388-0.514-0.288-1.243,0.227-1.634l31.066-23.598L8.461,2.098    C7.95,1.708,7.85,0.977,8.237,0.463c0.395-0.517,1.126-0.615,1.64-0.225l33.51,25.456L9.877,51.151    C9.664,51.31,9.415,51.388,9.169,51.388z"/>
                    </g>
                </g>
            </svg>
            </div>  
        </div>
    <?php wp_reset_postdata(); 
    return ob_get_clean();
}
add_shortcode('realisations', 'getRealisations');

/***********************
    CHILDREN PAGES
***********************/

// Showing children of current page in Posts Widget
add_action( 'elementor/query/child_pages', function( $query ) {
    // Get current post tags
    $current_page = get_queried_object_id();
    // Modify the query
    $query->set( 'post_parent', $current_page );
} );


/***********************
    AUTORISER LES SVG
***********************/

function wpc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'wpc_mime_types');


/***********************
    GRAPHANDCO CONFIG
***********************/
require_once(get_stylesheet_directory() .'/custom/graphandco-theme.php');
// require_once(get_stylesheet_directory() .'/custom/meo-avis.php');

?>