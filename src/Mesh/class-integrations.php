<?php
/**
 * Integration class for Mesh
 *
 * @since      1.2.0
 * @package    Mesh
 * @subpackage Integrations
 */

namespace Mesh;

use \Mesh\FileSystem as FileSystem;

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Class Integrations
 * @package Mesh
 */
class Integrations {

	/**
	 * Store our integration
	 *
	 * @var array
	 */
	private $integrations;

	/**
	 * Integrations constructor.
	 */
	public function __construct() {
		$this->integrations = FileSystem::scandir( LINCHPIN_MESH___PLUGIN_DIR . '/integrations', 'php', 0, false );

		if ( ! empty( $this->integrations ) ) {
			foreach ( $this->integrations as $file ) {
				if ( file_exists( $file ) ) {
					include_once $file;
				} else {
					error_log( sprintf( '%s %s', esc_html__( 'Mesh - Unable to locate integration: ', 'mesh' ), $file ) );
				}
			}
		}
	}
}
