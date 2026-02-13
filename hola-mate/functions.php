<?php
// @ini_set('display_errors', 'on');
// @error_reporting(E_ALL);

/*add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}*/
// To change add to cart text on single product page
// add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' ); 
// function woocommerce_custom_single_add_to_cart_text() {
//     return __( 'Buy Now', 'woocommerce' ); 
// }

// To change add to cart text on product archives(Collection) page

// add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );  
// function woocommerce_custom_product_add_to_cart_text() {
//     $new_text = '';
//     return $new_text;
// }

add_action('elementor/editor/after_enqueue_styles', function() {
    echo '<style>
        #elementor-panel-state-loading {
            display: none !important;
        }
    </style>';
});



add_filter('login_display_language_dropdown', '__return_false');

/***********************
    AVIS
**********************/

function avis() {
   ob_start();?>
      <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
      <div class="elfsight-app-0cd666cf-7fc3-4f33-b085-0a597fb5cf2d" data-elfsight-app-lazy></div>
   <?php return ob_get_clean();
}
add_shortcode('avis', 'avis');

function script_header() { ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MMLKMJW98Q"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MMLKMJW98Q');
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
    _paq.push(['setSiteId', '2']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
<?php
 }
add_action( 'wp_head', "script_header" );




/***********************
    ATTRIBUTS
**********************/
// function show_attributes() {
//     ob_start();
//         global $product;
//         if(wc_display_product_attributes( $product ) != null) {

//             echo wc_display_product_attributes( $product );
//         }
//     return ob_get_clean();
// }
// add_shortcode('attributes', 'show_attributes');
/***********************
    INTENSITÉ PRODUIT
**********************/
function function_intensity() {
        ob_start();

        /*
        $litrage = $product->get_attribute( 'litrage' );
        if(strlen($litrage)) {
            ?>
            <div class="product-quantity">
                <?php echo $litrage ?>
            </div>

            <?php
        }
        */


        $intensite_field = get_field('intensite');
        $intensite = intval($intensite_field);
        if($intensite > 0) { 
            ?>

            <?php if ($intensite == 1) {$intensiteLabel = 'Très doux';}?>
            <?php if ($intensite == 2) {$intensiteLabel = 'Doux';}?>
            <?php if ($intensite == 3) {$intensiteLabel = 'Moyen';}?>
            <?php if ($intensite == 4) {$intensiteLabel = 'Corsé';}?>
            <?php if ($intensite == 5) {$intensiteLabel = 'Très corsé';}?>
            <h2 class="intensity-title">Intensité</h2>
            <div class="product-intensity">
                <div class="intensity-rate">
                    <img class="rate-img" src="<?php echo get_stylesheet_directory_uri(); ?>/img/intensite.png" />
                    <img class="rate-img <?php if($intensite < 2) {echo'hide';}?>" src="<?php echo get_stylesheet_directory_uri(); ?>/img/intensite.png" />
                    <img class="rate-img <?php if($intensite < 3) {echo'hide';}?>" src="<?php echo get_stylesheet_directory_uri(); ?>/img/intensite.png" />
                    <img class="rate-img <?php if($intensite < 4) {echo'hide';}?>" src="<?php echo get_stylesheet_directory_uri(); ?>/img/intensite.png" />
                    <img class="rate-img <?php if($intensite < 5) {echo'hide';}?>" src="<?php echo get_stylesheet_directory_uri(); ?>/img/intensite.png" />
                </div>
                
                <div class="intensity-label">
                    <?php  echo $intensiteLabel  ?>
                </div>
            </div>
        <?php };
        return ob_get_clean();
    }
add_shortcode('intensite', 'function_intensity');

/***********************
    TYPE DE CONSO
**********************/
function function_consommation() {
        ob_start();
        $conso = get_field('type_de_consommation');
        if($conso && count($conso) > 0) { ?>
            <h2 class="conso-title">Type de consommation</h2>
            <div class="product-conso">
                <?php foreach ($conso as $key => $value) { 

            if ($value == 'calebasse') {$consoLabel = 'Calebasse';}
            if ($value == 'presse') {$consoLabel = 'Presse';}
            if ($value == 'infuseur') {$consoLabel = 'Infusion';} ?>

                <div class="conso-item">
                    <img class="conso-img" src="<?php echo get_stylesheet_directory_uri(); ?>/img/<?php echo $value ?>.png" />
                    <div class="conso-label"><?php echo $consoLabel ?></div>
                </div>
                <?php } ?>                
            </div>
            <?php };
        return ob_get_clean();
    }
add_shortcode('consommation', 'function_consommation');

/***********************
    TEMPÉRATURE
**********************/
function function_temperature() {
    ob_start();
    $temp = get_field('froid__chaud');
    if($temp && count($temp) > 0) { ?>

<h2 class="temp-title">Température</h2>
<div class="product-temp">
    <?php foreach ($temp as $key => $value) { ?>
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/<?php echo $value ?>.png" />
                <?php } ?>                
            </div>
            <?php };
        return ob_get_clean();
    }
    add_shortcode('temperature', 'function_temperature');

/***********************
    CONSEIL DE TEMPÉRATURE
**********************/
function function_conseil_temperature() {
    ob_start();
    $show_temp = get_field('temperature_recommandee');
    if($show_temp) {
        $mini = get_field('mini');
        $maxi = get_field('maxi');
        ?>
        <span>Entre <?php echo $mini ?> et <?php echo $maxi ?>°C</span>
        <?php
    }
?>

<!-- <h2 class="temp-title">Température</h2>
<div class="product-temp"> -->
<?php return ob_get_clean();
    }
    add_shortcode('conseil_temperature', 'function_conseil_temperature');
    
/***********************
    QUERY PARTENAIRES
**********************/

// function get_taxonomies( $args = array(), $output = 'names', $operator = 'and' ) {
//     global $wp_taxonomies;
 
//     $field = ( 'names' === $output ) ? 'name' : false;
 
//     return wp_filter_object_list( $wp_taxonomies, $args, $operator, $field );
// }



function get_products_cats() {
    ob_start();

    $terms = get_terms(
        array(
            'taxonomy'   => 'product_cat',
            'orderby'    => 'name',
            'hide_empty' => false,
        )
    );
    ?>
    <div class="terms-wrapper">
        <?php foreach( $terms as $term ) {

            $cats_to_exclude = array(
                29 /*Yerba mate (parent)*/, 
                30 /*Accessoires*/, 
                 40 /*Boissons*/, 
                 41 /*Calebasses*/, 
                 42/*Bombillas*/,
                 76/*Infusettes*/
            );
            if (in_array($term->term_id, $cats_to_exclude)) {
                continue;
            }

            $thumb_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
            $term_img = wp_get_attachment_url(  $thumb_id );

            $cat_id = $term->term_id;
            $format = get_field('format', 'product_cat_'.$cat_id);
            ?>
            <a 
                class="<?php echo $format == "carre" ? "term-item term-item-carre" : "term-item" ?>" 
                href="<?php echo get_site_url(); ?>/boutique/?jsf=woocommerce-archive&tax=product_cat:<?php echo $term->term_id?>"
                style='background-image:url(<?php echo $term_img; ?>)'
                >
                
                <h2><?php echo $term->name ?><span>(<?php echo $term->count ?>)</span></h2>
                <div class="term-desc">
                    <?php
                    // echo wp_trim_words($term->description, 30, '...')
                    echo $term->description
                    ?>
                </div>
            </a>
        <?php } ?>
    </div>

<?php return ob_get_clean();
}
add_shortcode('products_cats', 'get_products_cats');

/***********************
    ACTUS-CONSEILS
***********************/
function conseils() {
    ob_start();

    if( have_rows('bloc_texte_+_image') ): ?>
    <div class="quinconce-wrapper">  
        <?php while ( have_rows('bloc_texte_+_image') ) : the_row();
            $texte = get_sub_field('texte');
            $image = get_sub_field('image');
            ?>
            <div class="quinconce-item">
                <div class="quinconce-text"><?php echo $texte; ?></div>
                <div class="quinconce-image" style="background-image: url(<?php echo $image; ?>)"></div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php endif;

    return ob_get_clean();
}
add_shortcode('conseils', 'conseils');

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
    SUPPRIMER FIL D'ARIANE WOOCOMMERCE
***********************/
add_action( 'init', 'my_remove_breadcrumbs' );
 
function my_remove_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

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
