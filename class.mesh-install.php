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

		add_action( 'mesh_activate', array( $this, 'setup_activation' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'show_welcome' ) );
	}

	/**
	 * Enqueue our notifications, but really enqueue everything
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'admin-mesh-notifications', plugins_url( 'js/admin-mesh-notifications.js', __FILE__ ), array(), LINCHPIN_MESH_VERSION, true );

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
	public function show_welcome() {

		if ( is_admin() && 1 === intval( get_option( 'mesh_activation' ) ) ) {

			delete_option( 'mesh_activation' );

			$mesh_section_count = wp_count_posts( 'mesh_section' );

			$mesh_sections = $mesh_section_count->publish + $mesh_section_count->draft + $mesh_section_count->trash + $mesh_section_count->{'auto-draft'};

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
	 * Sets install date. If the user doesn't have a date it sets it
	 * on install and activation.
	 *
	 * @since 1.2.5
	 */
	public function setup_activation() {

		$options = get_option( 'mesh_settings' );

		if ( empty( $options['first_activated_on'] ) ) {
			$options['first_activated_on'] = time();
			update_option( 'mesh_settings', $options );
		}
	}
}

$mesh_install = new Mesh_Install();
