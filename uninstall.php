<?php
/**
 * Handle uninstalling the plugin
 *
 * @package Mesh
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN || dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ) ) ) {
	status_header( 404 );
	exit;
}

// Delete all Mesh Settings.
// @todo Delete all Mesh Content Section Posts and Mesh Templates.
delete_option( 'mesh_settings' );
delete_option( 'mesh_post_types' );
