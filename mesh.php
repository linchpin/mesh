<?php
/**
 * Plugin Name: Mesh
 * Plugin URI: https://meshplugin.com?utm_source=mesh&utm_medium=plugin-admin-page&utm_campaign=wp-plugin
 * Description: Adds multiple sections for content on a post by post basis. Mesh also has settings to enable it for specific post types
 * Version: 1.3
 * Text Domain: mesh
 * Domain Path: /languages
 * Author: Linchpin
 * Author URI: https://linchpin.agency/?utm_source=mesh&utm_medium=plugin-admin-page&utm_campaign=wp-plugin
 * License: GPLv2 or later
 *
 * @package Mesh
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Define all globals.
 */
define( 'LINCHPIN_MESH_VERSION', '1.3' );
define( 'LINCHPIN_MESH_PLUGIN_NAME', esc_html__( 'Mesh', 'mesh' ) );
define( 'LINCHPIN_MESH__MINIMUM_WP_VERSION', '4.0' );
define( 'LINCHPIN_MESH___PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LINCHPIN_MESH___PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Debug will set show_ui to true on most post types that are typically hidden.
 *
 * @since 1.1
 */
define( 'LINCHPIN_MESH_DEBUG_MODE', false );

$GLOBALS['mesh_current_version'] = get_option( 'mesh_version', '0.0' ); // Get our current Mesh Version.

require_once 'src/utilities/utilities.php';
require_once 'src/utilities/utilities-columns.php';
require_once 'src/utilities/utilities-rows.php';
require_once 'src/utilities/utilities-templates.php';
require_once 'src/utilities/utilities-filesystem.php';

// Fire off our autoloader
require_once 'src/autoloader.php';

$mesh_loader = new \Mesh\Psr4AutoloaderClass();
$mesh_loader->register();
$mesh_loader->add_namespace( 'Mesh', LINCHPIN_MESH___PLUGIN_DIR . 'src/Mesh' );

$mesh_ajax         = new \Mesh\AJAX();
$mesh_integrations = new \Mesh\Integrations();

if ( is_admin() ) {
	$mesh_upgrades = new \Mesh\Upgrades();
	$mesh_install  = new \Mesh\Install();
}

$mesh = new Mesh\Mesh();

if ( is_admin() ) {
	$mesh_pointers = new \Mesh\Pointers();
}

add_action( 'init', array( '\Mesh\Settings', 'init' ) );

/**
 * Flush rewrite rules when the plugin is activated.
 */
function mesh_activation_hook() {
	add_option( 'mesh_activation', true );
	flush_rewrite_rules();
	do_action( 'mesh_activate' );
}

register_activation_hook( __FILE__, 'mesh_activation_hook' );

/**
 * Flush rewrite rules when the plugin is deactivated.
 */
function mesh_deactivation_hook() {
	flush_rewrite_rules();

	do_action( 'mesh_deactivate' );
}
register_deactivation_hook( __FILE__, 'mesh_deactivation_hook' );
