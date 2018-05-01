<?php
/**
 * Install class for Mesh
 *
 * @since      1.2.0
 * @package    Mesh
 * @subpackage Install
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Class Mesh_Install
 */
class Mesh_Install {

	/**
	 * Check to see if mesh has been installed before.
	 */
	public function __construct() {

		if ( ! is_admin() ) {
			return;
		}

		if ( get_option( 'mesh_settings' ) === false ) {
			add_action( 'mesh_activate', array( $this, 'setup_first_install' ) );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'show_update_notice' ) );
		add_action( 'admin_init', array( $this, 'show_welcome' ) );
	}

	/**
	 * Enqueue our notifications, but really enqueue everything
	 */
	function admin_enqueue_scripts() {
		wp_enqueue_script( 'admin-mesh-notifications', plugins_url( 'assets/js/admin-mesh-notifications.js', __FILE__ ), array(), LINCHPIN_MESH_VERSION, true );

		wp_localize_script( 'admin-mesh-notifications', 'mesh_notifications', array(
			'dismiss_nonce' => wp_create_nonce( 'mesh_dismiss_notification_nonce' ),
		) );
	}

	/**
	 * On first install show new users a welcome screen.
	 * Only show the message when installing an individual message.
	 *
	 * @since 1.2
	 */
	function show_welcome() {

		if ( is_admin() && 1 === intval( get_option( 'mesh_activation' ) ) ) {

			delete_option( 'mesh_activation' );

			$mesh_section_count = wp_count_posts( 'mesh_section' );

			$mesh_sections = $mesh_section_count->publish + $mesh_section_count->draft + $mesh_section_count->trash + $mesh_section_count->auto_draft;

			// Send new users to the welcome so they learn how to use mesh.
			if ( ! isset( $_GET['activate-multi'] ) && 0 === $mesh_sections ) { // WPCS: CSRF ok, input var okay.
				wp_safe_redirect( admin_url( 'options-general.php?page=mesh&tab=about' ) );
				exit;
			}
		}
	}

	/**
	 * When the option doesn't exist, it should be a new install.
	 *
	 * @return bool
	 */
	public function is_first_install() {

		$options = get_option( 'mesh_settings' );

		if ( ! empty( $options ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Sets the options on first install for showing the installation notice
	 */
	public function setup_first_install() {

		$options = get_option( 'mesh_settings' );
		$options['first_activated_on'] = time();

		update_option( 'mesh_settings', $options );
	}

	/**
	 * Show the update notification
	 *
	 * @since 1.2
	 */
	public function show_update_notice() {
		$mesh_version = get_option( 'mesh_version' );
		$mesh_settings = get_option( 'mesh_settings' );

		$notifications = get_user_option( 'linchpin_mesh_notifications' );

		if ( false !== $mesh_settings && empty( $notifications['update-notice'] ) ) : ?>
		<div class="mesh-update-notice notice notice-info is-dismissible" data-type="update-notice">
			<div class="table">
				<div class="table-cell">
					<img src="<?php echo esc_attr( LINCHPIN_MESH___PLUGIN_URL . 'assets/images/mesh-full-logo-full-color@2x.png' ); ?>" >
				</div>
				<div class="table-cell">
					<p class="no-margin">
						<?php
						// translators: %1$s: Version Number %2$s: Link to what's new tab.
						printf( wp_kses_post( __( 'Thanks for updating Mesh to v. (%1$s). We suggest checking out <a href="%2$s">what\'s new</a>', 'mesh' ) ),
							esc_html( $mesh_version ),
							esc_url( admin_url( 'options-general.php?page=mesh&tab=new' ) )
						);
					?>
					</p>
					<p class="no-margin">
					<?php esc_html_e( "We've focused on maintenance, even more developer flexibility, a better uninstall process and more!",'mesh' ); ?>
					</p>
				</div>
			</div>
		</div>
		<?php
		endif;
	}
}

$mesh_install = new Mesh_Install();
