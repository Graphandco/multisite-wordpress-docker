<?php
/**
** activation theme
**/

// wp_enqueue_script( ‘jquery’ );

/*-------------------------------------
Debug
---------------------------------------*/

function script_header() { ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3Y7818TTXZ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-8NCQ1W8EFR');
    </script>
        <?php
         }
add_action( 'wp_head', "script_header" );

// Désactivation de Gutemberg
add_filter('use_block_editor_for_post', '__return_false', 10);

add_filter('login_display_language_dropdown', '__return_false');

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/custom/style.css', array('parent-style'));
}

function wpb_adding_scripts() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(), null, true);
    wp_register_script('my_script', get_stylesheet_directory_uri() . '/custom/main.js', array('jquery'),'1.1', true);
    wp_enqueue_script('my_script');
    wp_register_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.7/gsap.min.js', array('jquery'),'1.1', true);
    wp_enqueue_script('gsap');
    wp_register_script('scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.7/ScrollTrigger.min.js', array('jquery'),'1.1', true);
    wp_enqueue_script('scrolltrigger');
} 
add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts', 999 ); 


/* PAYPAL */

function paypal() {
    ob_start(); ?>
    <section class="tarifs">
        <div class="wrapper-tarifs">
            <div class="tarifs__bloc">
                <div class="tarifs__bloc__tarif">
                    <!-- <span class="question">Question fermée: </span> -->
                    <span class="prix">15€</span>
                </div>
                <div id="paypal-button-container1"></div>
                <script src="https://www.paypal.com/sdk/js?client-id=AVUrFASt3yp_krwJSvVrY_gcmFK87oBwpbbGCJBC2G_zc2yKTE_U9FvxzkUBArKVs92HlSEtFRFJlvJx&currency=EUR" data-sdk-integration-source="button-factory"></script> 
                <script>
                    paypal.Buttons({
                        style: {
                            shape: 'rect',
                            color: 'black',
                            layout: 'vertical',
                            label: 'paypal',
                        },
                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '15'
                                    }
                                }]
                            });
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                alert('Transaction completed by ' + details.payer.name.given_name + '!');
                            });
                        }
                    }).render('#paypal-button-container1');
                </script>
            </div>
            <div class="tarifs__bloc">
                <div class="tarifs__bloc__tarif">
                    <!-- <span class="question">Question ouverte: </span> -->
                    <span class="prix">30€</span>
                </div>
                <div id="paypal-button-container2"></div>
                <script>
                    paypal.Buttons({
                        style: {
                            shape: 'rect',
                            color: 'black',
                            layout: 'vertical',
                            label: 'paypal',
                        },
                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '30'
                                    }
                                }]
                            });
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                alert('Transaction completed by ' + details.payer.name.given_name + '!');
                            });
                        }
                    }).render('#paypal-button-container2');
                </script>
            </div>
            <div class="tarifs__bloc">
                <div class="tarifs__bloc__tarif">
                    <!-- <span class="question">Question ouverte: </span> -->
                    <span class="prix">50€</span>
                </div>
                <div id="paypal-button-container3"></div>
                <script>
                    paypal.Buttons({
                        style: {
                            shape: 'rect',
                            color: 'black',
                            layout: 'vertical',
                            label: 'paypal',
                        },
                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '50'
                                    }
                                }]
                            });
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                alert('Transaction completed by ' + details.payer.name.given_name + '!');
                            });
                        }
                    }).render('#paypal-button-container3');
                </script>
            </div>
        </div>
    </section>
    <?php return ob_get_clean();
}
add_shortcode('paypal-shortcode', 'paypal');

function wpc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'wpc_mime_types');


