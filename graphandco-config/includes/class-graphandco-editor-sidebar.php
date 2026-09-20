<?php
/**
 * Masquage de menus du back-office pour le rôle Éditeur (pas administrateur).
 *
 * @package GraphandCo_Config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retire des entrées de la colonne de menu admin pour les éditeurs.
 */
final class Graphandco_Editor_Sidebar {

	/**
	 * Slugs de menu de premier niveau proposés dans les réglages.
	 *
	 * @var array<string, string>
	 */
	public const MENU_CHOICES = array(
		'edit.php'          => 'Articles',
		'tools.php'         => 'Outils',
		'wpseo_dashboard'   => 'Yoast SEO — Tableau de bord',
		'wpseo_workouts'    => 'Yoast SEO — Workouts',
		'wpseo_redirects'   => 'Yoast SEO — Redirections',
		'wpseo_settings'    => 'Yoast SEO — Réglages',
	);

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'hide_menus_for_editors' ), 999 );
	}

	public function hide_menus_for_editors(): void {
		if ( ! $this->current_user_is_editor_not_admin() ) {
			return;
		}

		$opts  = Graphandco_Config::get_options();
		$slugs = isset( $opts['editor_hidden_menus'] ) && is_array( $opts['editor_hidden_menus'] ) ? $opts['editor_hidden_menus'] : array();
		$extra = isset( $opts['editor_hidden_menus_extra'] ) ? (string) $opts['editor_hidden_menus_extra'] : '';
		$lines = preg_split( '/\r\n|\r|\n/', $extra );
		if ( is_array( $lines ) ) {
			foreach ( $lines as $line ) {
				$line = trim( $line );
				if ( $line !== '' && preg_match( '/^[a-zA-Z0-9_.\-\/]+$/', $line ) ) {
					$slugs[] = $line;
				}
			}
		}
		$slugs = array_unique( array_filter( $slugs ) );
		foreach ( $slugs as $slug ) {
			remove_menu_page( $slug );
		}
	}

	/**
	 * Rôle « éditeur » uniquement, pas les administrateurs ni super-admins réseau.
	 */
	private function current_user_is_editor_not_admin(): bool {
		if ( function_exists( 'is_super_admin' ) && is_super_admin() ) {
			return false;
		}
		$user = wp_get_current_user();
		if ( ! $user || ! $user->ID ) {
			return false;
		}
		$roles = (array) $user->roles;
		if ( in_array( 'administrator', $roles, true ) ) {
			return false;
		}
		return in_array( 'editor', $roles, true );
	}
}
