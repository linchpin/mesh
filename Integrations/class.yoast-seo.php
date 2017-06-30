<?php
/**
 * Handle the Yoast SEO Integration.
 * This is a default integration that will automatically
 * be enabled if Yoast or Yoast Premium are activated.
 *
 * This integration does not have any toggles or settings.
 *
 * @since 1.2
 *
 */
namespace Mesh\Integrations;

if ( ! defined('ABSPATH' ) ) {
	exit;
}

/**
 * Mesh_AJAX class.
 */
class Yoast_SEO {

	/**
	 * WordPress_Seo constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Check to see if Yoast SEO or Yoast Premium SEO
	 * are active in order to be taken into account during
	 * page scoring.
	 *
	 * @since 1.2
	 *
	 * @return bool
	 */
	public function is_yoast_seo_active() {

		if ( ! is_plugin_active('wordpress-seo/wp-seo.php' ) &&
		 ! is_plugin_active('wordpress-seo-premium/wp-seo-premium.php' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Add our scripts as needed.
	 *
	 * @since 1.2
	 *
	 * @param $page_hook
	 */
	public function admin_enqueue_scripts( $page_hook ) {

		if ( $page_hook !== 'post.php' && $page_hook !== 'post-new.php' ) {
			return;
		}

		$mesh_screens = array( 'post', 'edit' );
		$current_screen = get_current_screen();
		$current_post_type = get_post_type_object( $current_screen->post_type );

		if ( ! in_array( $current_screen->base, $mesh_screens ) ) {
			return;
		}

		if ( $current_post_type->public === false ) {
			return;
		}


		wp_register_script( 'mesh-yoast-support', LINCHPIN_MESH___PLUGIN_URL . 'assets/js/integrations/yoast-seo.js', array( 'jquery', 'yoast-seo-post-scraper' ), LINCHPIN_MESH_VERSION );
		wp_enqueue_script( 'mesh-yoast-support' );
	}
}

$yoast_seo = new Yoast_SEO();