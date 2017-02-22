<?php
/**
 * Handle uninstalling the plugin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN || dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ) ) ) {
	status_header( 404 );
	exit;
}

// Delete all Mesh Settings
delete_option( 'mesh_settings' );
delete_option( 'mesh_post_types' );

// @todo Delete all Mesh Content Section Posts and Mesh Templates.
