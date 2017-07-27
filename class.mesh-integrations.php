<?php
/**
 * Integration class for Mesh
 *
 * @since      1.2.0
 * @package    Mesh
 * @subpackage Integrations
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Class Mesh_Integrations
 */
class Mesh_Integrations {

	/**
	 * @var
	 */
	private $integrations;

	/**
	 * Mesh_Integrations constructor.
	 */
	function __construct() {
		$this->integrations = Mesh::scandir( LINCHPIN_MESH___PLUGIN_DIR . '/integrations', 'php', 0, false );

		if ( ! empty( $this->integrations ) ) {
			foreach ( $this->integrations as $file ) {
				if ( file_exists( $file ) ) {
					include_once $file;
				}
			}
		}
	}
}

$mesh_integrations = new Mesh_Integrations();
