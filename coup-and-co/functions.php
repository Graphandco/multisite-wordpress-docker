<?php

function afficher_annee() {
   return '<a href="https://graphandco.com" target="blank" class="annee-actuelle"> Graph and Co ' . date_i18n("Y") . ' </a>';
}
add_shortcode('annee_actuelle', 'afficher_annee');



/******************************************************************************
    CONFIG -- NE PAS SUPPRIMER --
******************************************************************************/
require_once(get_stylesheet_directory() .'/graph-config/graph-functions.php');