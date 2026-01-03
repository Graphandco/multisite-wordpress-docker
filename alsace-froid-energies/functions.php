<?php

// @ini_set('display_errors', 'on');
// @error_reporting(E_ALL);
function change_archive_links() {
   global $wp_rewrite;
   // add 'archive'
   $wp_rewrite->date_structure ='conseils';
}
add_action('init','change_archive_links');


function script_header() { ?>
    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-YV3GTGJEJS"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-YV3GTGJEJS');
    </script> -->
    <?php
     }
add_action( 'wp_head', "script_header" );

/******************************************************************************
    QUINCONCE CONSEILS
******************************************************************************/

function get_blocs_quinconce() {
   ob_start();
      $quinconces = 'bloc_texte_+_image';  
      if( have_rows($quinconces) ): ?>
      <div class="quinconce-wrapper">  
         <?php while ( have_rows($quinconces) ) : the_row();
            $texte = get_sub_field('texte');
            $image = get_sub_field('image');
            $text_only = get_sub_field('texte_seul');
            ?>
            <div class="quinconce-bloc <?php echo $text_only ? 'text-only' : ''; ?>">
               <div class="quinconce-text"><?= $texte ?></div>
               <?php if ( ! $text_only ) { ?>
                  <div class="quinconce-image">
                     <img src="<?= $image ?>" alt="illustration du paragraphe">
                  </div>
               <?php } ?>
            </div>

            
         <?php endwhile; ?>
      </div>
      <?php endif;
   return ob_get_clean();
}
add_shortcode('blocs-quinconce', 'get_blocs_quinconce');


/******************************************************************************
    CONFIG -- NE PAS SUPPRIMER --
******************************************************************************/
require_once(get_stylesheet_directory() .'/graph-config/graph-functions.php');
