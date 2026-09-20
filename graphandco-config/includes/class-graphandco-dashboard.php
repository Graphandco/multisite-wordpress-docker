<?php
/**
 * Tableau de bord : widgets, assets admin associés.
 *
 * @package GraphandCo_Config
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widgets et styles sur l’écran d’accueil wp-admin.
 */
final class Graphandco_Dashboard {

	private const CONVERT_IMAGE_URL = 'https://convert-image.graphandco.com/';

	private const DASHBOARD_CONVERT_PREVIEW_FILE = 'dashboard-convert-preview.png';

	private const LOGO_GRAPHANDCO_FILE = 'logo-graphandco.svg';

	private const CONTACT_AGENCY_NAME = 'Graph and Co';

	private const CONTACT_EMAIL = 'contact@graphandco.com';

	private const CONTACT_PHONE_DISPLAY = '06 61 61 99 98';

	private const CONTACT_PHONE_TEL = '+33661619998';

	private const CONTACT_WEB_URL = 'https://graphandco.com/';

	public function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'register_widgets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( 'index.php' !== $hook_suffix ) {
			return;
		}
		wp_enqueue_style(
			'graphandco-config-dashboard',
			GRAPHANDCO_CONFIG_URL . 'assets/dashboard-widget.css',
			array(),
			GRAPHANDCO_CONFIG_VERSION
		);
	}

	public function register_widgets(): void {
		wp_add_dashboard_widget(
			'graphandco_convert_image',
			__( 'Convert Image — optimiser pour le web', 'graphandco-config' ),
			array( $this, 'render_convert_image_widget' ),
			null,
			null,
			'normal',
			'high'
		);

		wp_add_dashboard_widget(
			'graphandco_contact',
			__( 'Graph and Co — Contact', 'graphandco-config' ),
			array( $this, 'render_contact_widget' ),
			null,
			null,
			'side',
			'high'
		);
	}

	public function render_convert_image_widget(): void {
		$tool_url = self::CONVERT_IMAGE_URL;
		$img_url  = GRAPHANDCO_CONFIG_URL . 'assets/' . self::DASHBOARD_CONVERT_PREVIEW_FILE;
		?>
		<div class="graphandco-dashboard-convert">
			<div class="graphandco-dashboard-convert__visual">
				<img
					src="<?php echo esc_url( $img_url ); ?>"
					alt="<?php echo esc_attr__( 'Aperçu de l’outil Convert Image', 'graphandco-config' ); ?>"
					width="640"
					height="360"
					loading="lazy"
					decoding="async"
				/>
			</div>
			<p class="graphandco-dashboard-convert__text">
				<?php esc_html_e( 'Redimensionnez et convertissez vos images (WebP, AVIF, etc.) pour réduire le poids et accélérer le site.', 'graphandco-config' ); ?>
			</p>
			<p class="graphandco-dashboard-convert__action">
				<a class="button button-primary" href="<?php echo esc_url( $tool_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Aller sur le site Convert Image', 'graphandco-config' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	public function render_contact_widget(): void {
		$logo_url = GRAPHANDCO_CONFIG_URL . 'assets/' . self::LOGO_GRAPHANDCO_FILE;
		?>
		<div class="graphandco-dashboard-contact">
			<div class="graphandco-dashboard-contact__brand">
				<img
					class="graphandco-dashboard-contact__logo"
					src="<?php echo esc_url( $logo_url ); ?>"
					alt="<?php echo esc_attr( self::CONTACT_AGENCY_NAME ); ?>"
					width="120"
					height="40"
					loading="lazy"
					decoding="async"
				/>
				<p class="graphandco-dashboard-contact__name"><?php echo esc_html( self::CONTACT_AGENCY_NAME ); ?></p>
			</div>
			<p class="graphandco-dashboard-contact__intro">
				<?php esc_html_e( 'Un pépin sur le site, un doute sur un réglage, ou juste envie de dire bonjour ? Voici nos coordonnées — on répond dès qu’on a reposé la tasse de café (promis, pas de robot méchant au bout du fil).', 'graphandco-config' ); ?>
			</p>
			<ul class="graphandco-dashboard-contact__list">
				<li>
					<span class="graphandco-dashboard-contact__label"><?php esc_html_e( 'E-mail', 'graphandco-config' ); ?></span>
					<a href="<?php echo esc_url( 'mailto:' . self::CONTACT_EMAIL ); ?>"><?php echo esc_html( self::CONTACT_EMAIL ); ?></a>
				</li>
				<li>
					<span class="graphandco-dashboard-contact__label"><?php esc_html_e( 'Téléphone', 'graphandco-config' ); ?></span>
					<a href="<?php echo esc_url( 'tel:' . self::CONTACT_PHONE_TEL ); ?>"><?php echo esc_html( self::CONTACT_PHONE_DISPLAY ); ?></a>
				</li>
				<li>
					<span class="graphandco-dashboard-contact__label"><?php esc_html_e( 'Site web', 'graphandco-config' ); ?></span>
					<a href="<?php echo esc_url( self::CONTACT_WEB_URL ); ?>" target="_blank" rel="noopener noreferrer">graphandco.com</a>
				</li>
			</ul>
		</div>
		<?php
	}
}
