<?php
/**
 * Désactivation des commentaires (posts, pages, médias, admin, endpoint).
 *
 * Activé uniquement lorsque l’option « disable_comments » est cochée dans GraphandCo.
 *
 * @package GraphandCo_Config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handlers pour la désactivation complète des commentaires.
 */
final class Graphandco_Disable_Comments {

	/**
	 * Enregistre les hooks si l’option est active.
	 */
	public static function boot(): void {
		if ( ! Graphandco_Config::is_disable_comments_enabled() ) {
			return;
		}
		self::register_hooks();
	}

	/**
	 * Attache toutes les actions / filtres (équivalent de l’ancien plugin autonome).
	 */
	private static function register_hooks(): void {
		add_action( 'init', array( self::class, 'close_comments_frontend' ), 20 );
		add_action( 'admin_init', array( self::class, 'remove_post_type_comment_support' ) );
		add_action( 'admin_menu', array( self::class, 'remove_comments_admin_menu' ) );
		add_action( 'wp_before_admin_bar_render', array( self::class, 'remove_comments_admin_bar' ) );
		add_action( 'admin_init', array( self::class, 'block_comment_admin_access' ) );
		add_action( 'init', array( self::class, 'block_comment_post_endpoint' ), 1 );
	}

	/**
	 * Ferme les commentaires côté front.
	 */
	public static function close_comments_frontend(): void {
		add_filter( 'comments_open', '__return_false', 20, 2 );
		add_filter( 'pings_open', '__return_false', 20, 2 );
		add_filter( 'comments_array', '__return_empty_array', 10, 2 );
	}

	/**
	 * Supprime le support des commentaires pour tous les types de publication concernés.
	 */
	public static function remove_post_type_comment_support(): void {
		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	/**
	 * Supprime le menu Commentaires.
	 */
	public static function remove_comments_admin_menu(): void {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Supprime l’entrée Commentaires dans la barre d’admin.
	 */
	public static function remove_comments_admin_bar(): void {
		if ( ! is_admin_bar_showing() ) {
			return;
		}
		global $wp_admin_bar;
		if ( isset( $wp_admin_bar ) && is_object( $wp_admin_bar ) && method_exists( $wp_admin_bar, 'remove_menu' ) ) {
			$wp_admin_bar->remove_menu( 'comments' );
		}
	}

	/**
	 * Redirige l’accès direct à la liste des commentaires.
	 */
	public static function block_comment_admin_access(): void {
		global $pagenow;
		if ( isset( $pagenow ) && 'edit-comments.php' === $pagenow ) {
			wp_safe_redirect( admin_url() );
			exit;
		}
	}

	/**
	 * Bloque les soumissions via wp-comments-post.php.
	 */
	public static function block_comment_post_endpoint(): void {
		if ( ! isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
			return;
		}
		$script = wp_normalize_path( (string) $_SERVER['SCRIPT_FILENAME'] );
		if ( 'wp-comments-post.php' !== basename( $script ) ) {
			return;
		}
		wp_die(
			esc_html__( 'Les commentaires sont désactivés sur ce site.', 'graphandco-config' ),
			esc_html__( 'Commentaires désactivés', 'graphandco-config' ),
			array( 'response' => 403 )
		);
	}
}
