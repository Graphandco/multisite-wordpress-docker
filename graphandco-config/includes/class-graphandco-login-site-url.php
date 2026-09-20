<?php
/**
 * Headless / split-domain : URLs de connexion et redirections basées sur site_url (WordPress), pas home_url (site public).
 * Compatible WPS Hide Login via le filtre wps_hide_login_home_url.
 *
 * @package GraphandCo_Config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Compatibilité login lorsque WP_HOME et WP_SITEURL ont des hôtes différents.
 */
final class Graphandco_Login_Site_Url {

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'register' ), 25 );
	}

	public function register(): void {
		if ( ! Graphandco_Config::is_login_site_url_compat_enabled() ) {
			return;
		}

		add_filter( 'wps_hide_login_home_url', array( $this, 'filter_wps_base_url' ), 10, 1 );
		add_filter( 'login_url', array( $this, 'filter_login_url' ), 99, 3 );
		add_filter( 'login_redirect', array( $this, 'filter_login_redirect' ), 999, 3 );
		add_filter( 'allowed_redirect_hosts', array( $this, 'filter_allowed_redirect_hosts' ), 10, 2 );
		add_filter( 'redirect_canonical', array( $this, 'block_wrong_host_canonical' ), 10, 2 );
		add_filter( 'wp_redirect', array( $this, 'fix_wp_redirect_location' ), 999, 2 );
	}

	/**
	 * @param string $url Valeur par défaut home_url( '/' ) dans WPS Hide Login.
	 * @return string
	 */
	public function filter_wps_base_url( $url ) {
		return site_url( '/' );
	}

	/**
	 * @param string         $login_url
	 * @param string|false   $redirect
	 * @param bool           $force_reauth
	 * @return string
	 */
	public function filter_login_url( $login_url, $redirect, $force_reauth ) {
		return $this->rewrite_home_host_to_site( (string) $login_url );
	}

	/**
	 * Après connexion : corrige redirect_to encore sur le domaine home_url (ex. /wp-admin sur le mauvais hôte).
	 *
	 * @param string           $redirect_to
	 * @param string           $requested_redirect_to
	 * @param \WP_User|\WP_Error $user
	 * @return string
	 */
	public function filter_login_redirect( $redirect_to, $requested_redirect_to, $user ) {
		if ( $user instanceof \WP_Error ) {
			return $redirect_to;
		}
		$fixed = $this->rewrite_home_host_to_site( (string) $redirect_to );
		return $fixed !== '' ? $fixed : $redirect_to;
	}

	/**
	 * @param string[] $hosts
	 * @param string   $host
	 * @return string[]
	 */
	public function filter_allowed_redirect_hosts( $hosts, $host ) {
		$home = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
		$site = wp_parse_url( site_url( '/' ), PHP_URL_HOST );
		if ( empty( $home ) || empty( $site ) || $home === $site ) {
			return $hosts;
		}
		if ( ! is_array( $hosts ) ) {
			$hosts = array();
		}
		if ( $home && ! in_array( $home, $hosts, true ) ) {
			$hosts[] = $home;
		}
		if ( $site && ! in_array( $site, $hosts, true ) ) {
			$hosts[] = $site;
		}
		return $hosts;
	}

	/**
	 * @param string|false $redirect_url
	 * @param string       $requested_url
	 * @return string|false
	 */
	public function block_wrong_host_canonical( $redirect_url, $requested_url ) {
		if ( empty( $redirect_url ) || empty( $requested_url ) ) {
			return $redirect_url;
		}

		$home_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
		$site_host = wp_parse_url( site_url( '/' ), PHP_URL_HOST );

		if ( empty( $home_host ) || empty( $site_host ) || $home_host === $site_host ) {
			return $redirect_url;
		}

		$req = wp_parse_url( $requested_url );
		$red = wp_parse_url( $redirect_url );

		if ( empty( $req['host'] ) || empty( $red['host'] ) ) {
			return $redirect_url;
		}

		if ( $req['host'] !== $site_host || $red['host'] !== $home_host ) {
			return $redirect_url;
		}

		$path_req = isset( $req['path'] ) ? untrailingslashit( $req['path'] ) : '';
		$path_red = isset( $red['path'] ) ? untrailingslashit( $red['path'] ) : '';

		if ( $path_req === $path_red ) {
			return false;
		}

		return $redirect_url;
	}

	/**
	 * @param string $location
	 * @param int    $status
	 * @return string
	 */
	public function fix_wp_redirect_location( $location, $status ) {
		$location = (string) $location;
		if ( $location === '' ) {
			return $location;
		}

		$home_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
		$site_host = wp_parse_url( site_url( '/' ), PHP_URL_HOST );

		if ( empty( $home_host ) || empty( $site_host ) || $home_host === $site_host ) {
			return $location;
		}

		$loc = wp_parse_url( $location );
		if ( empty( $loc['host'] ) || $loc['host'] !== $home_host ) {
			return $location;
		}

		$req_raw = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$req_host = preg_replace( '/:\d+$/', '', $req_raw );
		$site_cmp = preg_replace( '/:\d+$/', '', strtolower( (string) $site_host ) );

		if ( $req_host === '' || $req_host !== $site_cmp ) {
			return $location;
		}

		$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		if ( $req_uri === '' ) {
			return $location;
		}

		$req_path = (string) wp_parse_url( 'http://placeholder.local' . $req_uri, PHP_URL_PATH );
		$loc_path = isset( $loc['path'] ) ? (string) $loc['path'] : '';

		if ( untrailingslashit( $req_path ) !== untrailingslashit( $loc_path ) ) {
			return $location;
		}

		$new = site_url( $loc_path );
		if ( ! empty( $loc['query'] ) ) {
			parse_str( $loc['query'], $query_args );
			if ( is_array( $query_args ) && $query_args !== array() ) {
				$new = add_query_arg( $query_args, $new );
			}
		}
		if ( ! empty( $loc['fragment'] ) ) {
			$new .= '#' . $loc['fragment'];
		}

		return $new;
	}

	/**
	 * Réécrit une URL dont l’hôte est celui de home_url vers site_url (même chemin, query, fragment).
	 *
	 * @param string $url
	 * @return string
	 */
	private function rewrite_home_host_to_site( string $url ): string {
		if ( $url === '' ) {
			return '';
		}

		$home_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
		$site_host = wp_parse_url( site_url( '/' ), PHP_URL_HOST );

		if ( empty( $home_host ) || empty( $site_host ) || $home_host === $site_host ) {
			return $url;
		}

		$parts = wp_parse_url( $url );
		if ( empty( $parts['host'] ) || $parts['host'] !== $home_host ) {
			return $url;
		}

		if ( empty( $parts['path'] ) ) {
			return $url;
		}

		$new = site_url( $parts['path'] );

		if ( ! empty( $parts['query'] ) ) {
			parse_str( $parts['query'], $query_args );
			if ( is_array( $query_args ) && $query_args !== array() ) {
				$new = add_query_arg( $query_args, $new );
			}
		}
		if ( ! empty( $parts['fragment'] ) ) {
			$new .= '#' . $parts['fragment'];
		}

		return $new;
	}
}
