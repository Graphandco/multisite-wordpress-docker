<?php
/**
 * Plugin Name: GraphandCo Config
 * Description: Personnalisation admin (menus éditeurs), page de connexion (médiathèque), split domain login, tableau de bord, commentaires.
 * Version: 1.5.0
 * Author: GraphandCo
 * Text Domain: graphandco-config
 *
 * @package GraphandCo_Config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GRAPHANDCO_CONFIG_VERSION', '1.5.0' );
define( 'GRAPHANDCO_CONFIG_PATH', plugin_dir_path( __FILE__ ) );
define( 'GRAPHANDCO_CONFIG_URL', plugin_dir_url( __FILE__ ) );
define( 'GRAPHANDCO_CONFIG_OPTION', 'graphandco_config_options' );

require_once GRAPHANDCO_CONFIG_PATH . 'includes/class-graphandco-editor-sidebar.php';
require_once GRAPHANDCO_CONFIG_PATH . 'includes/class-graphandco-login.php';
require_once GRAPHANDCO_CONFIG_PATH . 'includes/class-graphandco-login-site-url.php';
require_once GRAPHANDCO_CONFIG_PATH . 'includes/class-graphandco-dashboard.php';
require_once GRAPHANDCO_CONFIG_PATH . 'includes/class-graphandco-config.php';
require_once GRAPHANDCO_CONFIG_PATH . 'includes/class-graphandco-disable-comments.php';

Graphandco_Config::instance();

add_action( 'plugins_loaded', array( 'Graphandco_Disable_Comments', 'boot' ), 5 );
