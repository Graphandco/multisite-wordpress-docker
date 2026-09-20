<?php
/**
 * Plugin bootstrap : options, page de réglages, orchestration des modules.
 *
 * @package GraphandCo_Config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Point d’entrée principal (singleton). Le reste est découpé en classes dédiées.
 */
final class Graphandco_Config {

	/**
	 * Singleton.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * @return self
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		new Graphandco_Login();
		new Graphandco_Login_Site_Url();
		new Graphandco_Editor_Sidebar();
		new Graphandco_Dashboard();
	}

	/**
	 * Default option values (match previous thème enfant rletudes.fr).
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults(): array {
		return array(
			'editor_hidden_menus'       => array(
				'edit.php',
				'tools.php',
				'wpseo_dashboard',
				'wpseo_workouts',
				'wpseo_redirects',
				'wpseo_settings',
			),
			'editor_hidden_menus_extra' => '',
			'login_bg_attachment_id'    => 0,
			'login_logo_attachment_id'  => 0,
			'disable_comments'           => false,
			'login_site_url_compat'    => false,
		);
	}

	/**
	 * Compatibilité headless : URLs de connexion sur site_url (WPS Hide Login, redirect après login, etc.).
	 */
	public static function is_login_site_url_compat_enabled(): bool {
		$opts = self::get_options();
		return ! empty( $opts['login_site_url_compat'] );
	}

	/**
	 * Option « désactiver complètement les commentaires ».
	 */
	public static function is_disable_comments_enabled(): bool {
		$opts = self::get_options();
		return ! empty( $opts['disable_comments'] );
	}

	/**
	 * @return array<string, mixed>
	 */
	public static function get_options(): array {
		$saved = get_option( GRAPHANDCO_CONFIG_OPTION, array() );
		return wp_parse_args( is_array( $saved ) ? $saved : array(), self::defaults() );
	}

	/**
	 * @param array<string, mixed> $options Raw options.
	 * @return array<string, mixed>
	 */
	public static function sanitize_options( $options ): array {
		$defaults = self::defaults();
		$out      = $defaults;

		if ( ! is_array( $options ) ) {
			return $out;
		}

		$hidden = array();
		if ( ! empty( $options['editor_hidden_menus'] ) && is_array( $options['editor_hidden_menus'] ) ) {
			foreach ( $options['editor_hidden_menus'] as $slug ) {
				$slug = sanitize_text_field( (string) $slug );
				if ( $slug !== '' && isset( Graphandco_Editor_Sidebar::MENU_CHOICES[ $slug ] ) ) {
					$hidden[] = $slug;
				}
			}
		}
		$out['editor_hidden_menus'] = array_values( array_unique( $hidden ) );

		$extra = isset( $options['editor_hidden_menus_extra'] ) ? (string) $options['editor_hidden_menus_extra'] : '';
		$lines = preg_split( '/\r\n|\r|\n/', $extra );
		$clean = array();
		if ( is_array( $lines ) ) {
			foreach ( $lines as $line ) {
				$line = trim( $line );
				if ( $line === '' ) {
					continue;
				}
				if ( preg_match( '/^[a-zA-Z0-9_.\-\/]+$/', $line ) ) {
					$clean[] = $line;
				}
			}
		}
		$out['editor_hidden_menus_extra'] = implode( "\n", array_unique( $clean ) );

		$out['login_bg_attachment_id']   = isset( $options['login_bg_attachment_id'] ) ? absint( $options['login_bg_attachment_id'] ) : 0;
		$out['login_logo_attachment_id'] = isset( $options['login_logo_attachment_id'] ) ? absint( $options['login_logo_attachment_id'] ) : 0;

		$out['disable_comments']         = ! empty( $options['disable_comments'] );
		$out['login_site_url_compat'] = ! empty( $options['login_site_url_compat'] );

		return $out;
	}

	public function register_settings(): void {
		register_setting(
			'graphandco_config',
			GRAPHANDCO_CONFIG_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( self::class, 'sanitize_options' ),
				'default'           => self::defaults(),
			)
		);
	}

	public function add_settings_page(): void {
		add_options_page(
			__( 'GraphandCo — Configuration', 'graphandco-config' ),
			__( 'GraphandCo', 'graphandco-config' ),
			'manage_options',
			'graphandco-config',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Scripts / styles de la page de réglages uniquement (médiathèque login).
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_admin_assets( string $hook_suffix ): void {
		if ( 'settings_page_graphandco-config' !== $hook_suffix ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_script(
			'graphandco-config-admin',
			GRAPHANDCO_CONFIG_URL . 'js/admin-settings.js',
			array( 'jquery' ),
			GRAPHANDCO_CONFIG_VERSION,
			true
		);
		wp_localize_script(
			'graphandco-config-admin',
			'graphandcoConfigI18n',
			array(
				'chooseImage' => __( 'Choisir une image', 'graphandco-config' ),
				'useImage'    => __( 'Utiliser cette image', 'graphandco-config' ),
			)
		);
	}

	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$opts = self::get_options();
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php settings_fields( 'graphandco_config' ); ?>
				<h2><?php esc_html_e( 'Page de connexion', 'graphandco-config' ); ?></h2>
				<p class="description">
					<?php esc_html_e( 'Choisissez les images dans la médiathèque. Laisser vide conserve le style sans image personnalisée (fond / logo).', 'graphandco-config' ); ?>
				</p>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Image de fond', 'graphandco-config' ); ?></th>
						<td>
							<input type="hidden" name="<?php echo esc_attr( GRAPHANDCO_CONFIG_OPTION ); ?>[login_bg_attachment_id]" id="gc_login_bg_id" value="<?php echo esc_attr( (string) (int) $opts['login_bg_attachment_id'] ); ?>" />
							<button type="button" class="button" id="gc_pick_login_bg"><?php esc_html_e( 'Choisir une image', 'graphandco-config' ); ?></button>
							<button type="button" class="button" id="gc_clear_login_bg"><?php esc_html_e( 'Retirer', 'graphandco-config' ); ?></button>
							<div id="gc_login_bg_preview" style="margin-top:8px;">
								<?php
								if ( ! empty( $opts['login_bg_attachment_id'] ) ) {
									echo wp_get_attachment_image( (int) $opts['login_bg_attachment_id'], 'medium', false, array( 'style' => 'max-width:320px;height:auto;' ) );
								}
								?>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Logo', 'graphandco-config' ); ?></th>
						<td>
							<input type="hidden" name="<?php echo esc_attr( GRAPHANDCO_CONFIG_OPTION ); ?>[login_logo_attachment_id]" id="gc_login_logo_id" value="<?php echo esc_attr( (string) (int) $opts['login_logo_attachment_id'] ); ?>" />
							<button type="button" class="button" id="gc_pick_login_logo"><?php esc_html_e( 'Choisir une image', 'graphandco-config' ); ?></button>
							<button type="button" class="button" id="gc_clear_login_logo"><?php esc_html_e( 'Retirer', 'graphandco-config' ); ?></button>
							<div id="gc_login_logo_preview" style="margin-top:8px;">
								<?php
								if ( ! empty( $opts['login_logo_attachment_id'] ) ) {
									echo wp_get_attachment_image( (int) $opts['login_logo_attachment_id'], 'medium', false, array( 'style' => 'max-width:200px;height:auto;' ) );
								}
								?>
							</div>
						</td>
					</tr>
				</table>

				<h2><?php esc_html_e( 'Connexion — split domain (headless)', 'graphandco-config' ); ?></h2>
				<p class="description">
					<?php esc_html_e( 'À activer lorsque l’« Adresse web du site » (WP_HOME) et l’« Adresse web de WordPress » (WP_SITEURL) ne sont pas sur le même domaine (ex. front Next.js sur le domaine principal, WordPress sur un sous-domaine admin). Utilise site_url pour les URLs de connexion (dont WPS Hide Login via wps_hide_login_home_url), corrige la redirection après identification, et limite les redirections HTTP indésirables vers le domaine public.', 'graphandco-config' ); ?>
				</p>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Compatibilité site_url', 'graphandco-config' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( GRAPHANDCO_CONFIG_OPTION ); ?>[login_site_url_compat]" value="1" <?php checked( ! empty( $opts['login_site_url_compat'] ) ); ?> />
								<?php esc_html_e( 'Forcer les URLs de connexion et redirections sur le domaine WordPress (site_url)', 'graphandco-config' ); ?>
							</label>
						</td>
					</tr>
				</table>

				<h2><?php esc_html_e( 'Commentaires', 'graphandco-config' ); ?></h2>
				<p class="description">
					<?php esc_html_e( 'Lorsque cette option est activée, les commentaires sont entièrement désactivés (interface, types de publication, barre d’admin et soumissions).', 'graphandco-config' ); ?>
				</p>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Désactiver les commentaires', 'graphandco-config' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( GRAPHANDCO_CONFIG_OPTION ); ?>[disable_comments]" value="1" <?php checked( ! empty( $opts['disable_comments'] ) ); ?> />
								<?php esc_html_e( 'Désactiver totalement les commentaires sur ce site', 'graphandco-config' ); ?>
							</label>
						</td>
					</tr>
				</table>

				<h2><?php esc_html_e( 'Menus admin masqués pour les éditeurs', 'graphandco-config' ); ?></h2>
				<p class="description">
					<?php esc_html_e( 'S\'applique aux comptes avec le rôle Éditeur (pas aux administrateurs).', 'graphandco-config' ); ?>
				</p>
				<fieldset>
					<?php foreach ( Graphandco_Editor_Sidebar::MENU_CHOICES as $slug => $label ) : ?>
						<label style="display:block;margin:6px 0;">
							<input type="checkbox" name="<?php echo esc_attr( GRAPHANDCO_CONFIG_OPTION ); ?>[editor_hidden_menus][]" value="<?php echo esc_attr( $slug ); ?>"
								<?php checked( in_array( $slug, $opts['editor_hidden_menus'], true ) ); ?> />
							<?php echo esc_html( $label . ' (' . $slug . ')' ); ?>
						</label>
					<?php endforeach; ?>
				</fieldset>
				<p>
					<label for="gc_extra_menus"><strong><?php esc_html_e( 'Slugs supplémentaires (un par ligne)', 'graphandco-config' ); ?></strong></label><br />
					<textarea name="<?php echo esc_attr( GRAPHANDCO_CONFIG_OPTION ); ?>[editor_hidden_menus_extra]" id="gc_extra_menus" class="large-text code" rows="4" cols="40"><?php echo esc_textarea( $opts['editor_hidden_menus_extra'] ); ?></textarea>
				</p>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
