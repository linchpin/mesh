<?php
/**
 * Update class for Mesh
 *
 * @since      1.1.0
 * @package    Mesh
 * @subpackage Upgrades
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Class Mesh_Upgrades
 */
class Mesh_Upgrades {

	/**
	 * Mesh_Upgrades constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_notices', array( $this, 'show_update_notice' ) );
		add_action( 'admin_notices', array( $this, 'show_review_nag' ), 11 );
		add_action( 'admin_notices', array( $this, 'show_classic_editor_notice' ), 12 );
	}

	/**
	 * Perform any upgrades needed.
	 */
	public function admin_init() {

		if ( isset( $GLOBALS['mesh_current_version'] ) ) {
			if ( version_compare( $GLOBALS['mesh_current_version'], '1.0', '<' ) ) {
				$this->version_1_0();
			}

			if ( version_compare( $GLOBALS['mesh_current_version'], '1.1.0', '<' ) ) {
				$this->version_1_1();
			}

			if ( version_compare( $GLOBALS['mesh_current_version'], '1.2.4', '<' ) ) {
				$this->version_1_2_4();
			}

			if ( version_compare( $GLOBALS['mesh_current_version'], '1.2.5', '<' ) ) {
				$this->version_1_2_5();
			}

			// Latest Version
			if ( version_compare( $GLOBALS['mesh_current_version'], '1.2.5.6', '<' ) ) {
				$this->update_version( '1.2.5.6' );
			}
		}
	}

	/**
	 * Upgrade to version 1.0 by ensuring the default post types are selected.
	 */
	public function version_1_0() {

		$settings = get_option( 'mesh_post_types' );

		if ( ! empty( $settings ) ) {
			return;
		}

		$this->post_types = get_post_types();

		if ( empty( $this->post_types ) ) {
			return;
		}

		$default_post_types = array(
			'mesh_template' => 1,
		);

		foreach ( $this->post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			if ( in_array( $post_type, array( 'revision', 'nav_menu_item', 'attachment' ), true ) || ! $post_type_object->public ) {
				continue;
			}

			$default_post_types[] = $post_type;
		}

		if ( ! empty( $default_post_types ) ) {
			update_option( 'mesh_post_types', $default_post_types );
		}

		$this->update_version( '1.0' );
	}

	/**
	 * Upgrade to version 1.1
	 *
	 * Add mesh_templates to available post types that allow mesh_section
	 */
	public function version_1_1() {

		if ( empty( $this->post_types ) ) {

			$this->post_types = get_post_types();

			if ( empty( $this->post_types ) ) {
				return;
			}
		}

		$available_post_types = get_option( 'mesh_post_types', array() );

		// If for some reason we do not have default templates make sure we have pages at minimum.
		if ( empty( $available_post_types ) ) {
			$available_post_types['pages'] = 1;
		}

		$available_post_types['mesh_template'] = 1;

		update_option( 'mesh_post_types', $available_post_types );

		// Add our taxonomy terms.
		wp_insert_term( 'reference', 'mesh_template_types' );
		wp_insert_term( 'starter', 'mesh_template_types' );

		$this->update_version( '1.1' );
	}

	/**
	 * Upgrade to version 1.2.4
	 *
	 * Add css_mode as a default option that is saved. By default the css_mode should
	 * be set to 0 and it should output on the front end.
	 *
	 * Make sure mesh templates are also enabled by default.
	 */
	public function version_1_2_4() {

		$mesh_options = get_option( 'mesh_settings', array(
			'css_mode' => 0,
		) );

		if ( empty( $mesh_options['css_mode'] ) ) {
			$mesh_options['css_mode'] = 0;
		}

		update_option( 'mesh_settings', $mesh_options );

		// If for some reason we DO NOT have mesh_template enabled be sure we enable it again.
		$available_post_types                  = get_option( 'mesh_post_types', array() );
		$available_post_types['mesh_template'] = 1;
		update_option( 'mesh_post_types', $available_post_types );

		$this->update_version( '1.2.4' );
	}

	public function version_1_2_5() {

		$notifications = get_user_option( 'linchpin_mesh_notifications' );

		unset( $notifications['update-notice'] );

		update_user_option( get_current_user_ID(), 'linchpin_mesh_notifications', $notifications );

		$this->update_version( '1.2.5' );
	}

	/**
	 * Utility method to update version numbers
	 *
	 * @param string $version Version # to save.
	 */
	public function update_version( $version ) {

		if ( empty( $version ) ) {
			return;
		}

		$version = sanitize_text_field( $version );

		update_option( 'mesh_version', $version );
		$GLOBALS['mesh_current_version'] = $version;
	}

	/**
	 * Show the update notification
	 *
	 * @since 1.2
	 */
	public function show_update_notice() {
		$mesh_settings = get_option( 'mesh_settings' );
		$notifications = get_user_option( 'linchpin_mesh_notifications' );

		if ( false !== $mesh_settings && empty( $notifications['update-notice'] ) ) {
			include LINCHPIN_MESH___PLUGIN_DIR . 'admin/upgrade-notice.php';
		}
	}

	/**
	 * Show a notice if the user does not have the classic editor enabled.
	 *
	 * @since 1.4.0
	 */
	public function show_classic_editor_notice() {
		if ( ! class_exists( 'Classic_Editor' ) ) {
			include LINCHPIN_MESH___PLUGIN_DIR . 'admin/editor-notice.php';
		}
	}

	public function show_review_nag() {
		$mesh_settings = get_option( 'mesh_settings' );
		$notifications = get_user_option( 'linchpin_mesh_notifications' );

		// If we don't have a date die early.
		if ( ! isset( $mesh_settings['first_activated_on'] ) || '' === $mesh_settings['first_activated_on'] ) {
			return '';
		}

		$now          = new \DateTime();
		$install_date = new \DateTime();
		$install_date->setTimestamp( $mesh_settings['first_activated_on'] );

		if ( $install_date->diff( $now )->days < 30 ) {
			return '';
		}

		if ( false !== $mesh_settings && ( ! empty( $notifications['update-notice'] ) && empty( $notifications['review-notice'] ) ) ) {
			include LINCHPIN_MESH___PLUGIN_DIR . 'admin/review-notice.php';
		}

		return '';
	}
}

$mesh_upgrades = new Mesh_Upgrades();
