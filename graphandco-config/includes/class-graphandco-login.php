<?php
/**
 * Page de connexion : styles, médiathèque (fond / logo), filtres d’en-tête.
 *
 * @package GraphandCo_Config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Personnalisation visuelle wp-login.php.
 */
final class Graphandco_Login {

	public function __construct() {
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'login_headerurl', array( $this, 'header_url' ) );
		add_filter( 'login_headertext', array( $this, 'header_text' ) );
	}

	public function enqueue_assets(): void {
		wp_enqueue_style(
			'graphandco-config-login-base',
			GRAPHANDCO_CONFIG_URL . 'assets/login-base.css',
			array(),
			GRAPHANDCO_CONFIG_VERSION
		);

		$opts = Graphandco_Config::get_options();
		$css  = $this->build_inline_css( $opts );
		if ( $css !== '' ) {
			wp_add_inline_style( 'graphandco-config-login-base', $css );
		}
	}

	/**
	 * @param array<string, mixed> $opts Options.
	 */
	private function build_inline_css( array $opts ): string {
		$rules = array();

		$bg_id = (int) ( $opts['login_bg_attachment_id'] ?? 0 );
		if ( $bg_id > 0 ) {
			$src = wp_get_attachment_image_url( $bg_id, 'full' );
			if ( $src ) {
				$rules[] = 'body.login{background-image:url(' . esc_url( $src ) . ');}';
			}
		}

		$logo_id = (int) ( $opts['login_logo_attachment_id'] ?? 0 );
		if ( $logo_id > 0 ) {
			$src = wp_get_attachment_image_url( $logo_id, 'full' );
			if ( $src ) {
				$rules[] = '.login h1 a{background-image:url(' . esc_url( $src ) . ') !important;}';
			}
		}

		return implode( '', $rules );
	}

	public function header_url(): string {
		return home_url( '/' );
	}

	public function header_text(): string {
		return get_bloginfo( 'name' );
	}
}
