<?php

// @ini_set('display_errors', 'on');
// @error_reporting(E_ALL);

function styleACF() {
    echo '<style>
          .acf-accordion-title label {
            font-weight: bold !important;
            font-size: 1.1rem !important;
          }
    </style>';
  }
  add_action('admin_head', 'styleACF');

  add_filter('login_display_language_dropdown', '__return_false');

function script_header() { ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YV3GTGJEJS"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-YV3GTGJEJS');
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
        _paq.push(['setSiteId', '3']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    <!-- End Matomo Code -->

    <?php
     }
add_action( 'wp_head', "script_header" );

/******************************************************************************
    CONFIG -- NE PAS SUPPRIMER --
******************************************************************************/
require_once(get_stylesheet_directory() .'/graph-config/graph-functions.php');
