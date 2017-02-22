<?php
/**
 * Class Mesh_Defaults
 *
 * @package Mesh
 */

/**
 * Test Mesh defaults.
 *
 * @group mesh-defaults
 */
class Mesh_Defaults extends WP_UnitTestCase {

	/**
	 * Test constants are set.
	 */
	function test_default_constants() {
		$this->assertTrue( defined( 'LINCHPIN_MESH_VERSION' ) );
		$this->assertTrue( defined( 'LINCHPIN_MESH_PLUGIN_NAME' ) );
		$this->assertTrue( defined( 'LINCHPIN_MESH__MINIMUM_WP_VERSION' ) );
		$this->assertTrue( defined( 'LINCHPIN_MESH___PLUGIN_URL' ) );
		$this->assertTrue( defined( 'LINCHPIN_MESH___PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'LINCHPIN_MESH_DEBUG_MODE' ) );
	}

	/**
	* Test the correct version is in the database.
	*/
	function test_current_version() {
		$this->assertEquals( $GLOBALS['mesh_current_version'], LINCHPIN_MESH_VERSION );
	}
}
