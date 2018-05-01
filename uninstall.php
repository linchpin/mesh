<?php
/**
 * Handle uninstalling the plugin
 *
 * @package Mesh
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN || dirname( WP_UNINSTALL_PLUGIN ) !== dirname( plugin_basename( __FILE__ ) ) ) {
	status_header( 404 );
	exit;
}

/**
 *
 * @param $post_type
 */
function mesh_uninstall_delete_posts( $post_type ) {

	$post_types = array( 'mesh_template', 'mesh_section' );

	if ( ! in_array( $post_type, $post_types, true ) ) {
		return;
	}

	$total = 0;
	$args  = array(
		'post_type'      => $post_type,
		'post_status'    => array( 'any', 'auto-draft' ),
		'posts_per_page' => 200,
		'no_found_rows'  => true,
		'fields'         => 'ids',
		'offset'         => 0,
	);

	$mesh_posts = new WP_Query( $args );

	if ( $mesh_posts->have_posts() ) {

		while ( $mesh_posts->have_posts() && $total < 10000 ) {

			$mesh_posts->the_post();

			wp_delete_post( get_the_ID(), true );
			$total += $args['posts_per_page'];
		}

		$mesh_posts = new WP_Query( $args );
	}
}

/**
 * Delete all terms in in the given taxonomy
 *
 * @param $taxonomy
 */
function mesh_uninstall_delete_terms( $taxonomy ) {

	$taxonomies = array( 'mesh_template_usage' );

	if ( ! in_array( $taxonomy, $taxonomies, true ) ) { // Only allow our taxonomies to be removed using our method
		return;
	}

	if ( ! taxonomy_exists( $taxonomy ) ) { // Make sure the taxonomy actually exists.
		return;
	}

	$terms = get_terms( array( 'taxonomy' => $taxonomy, 'fields' => 'ids', 'hide_empty' => false ) );

	foreach ( $terms as $value ) {
		wp_delete_term( $value, $taxonomy );
	}
}

/**
 * If our mesh uninstall setting is enabled clear everything out on uninstall/delete of the plugin
 */

$mesh_settings = get_option( 'mesh_settings' );

if ( ! empty( $mesh_settings['uninstall'] ) ) {

	// Delete all Mesh related custom post types
	mesh_uninstall_delete_posts( 'mesh_section' );  // Delete all mesh sections (this includes children)
	mesh_uninstall_delete_posts( 'mesh_template' ); // Delete all mesh templates we have

	// Delete taxonomy terms
	mesh_uninstall_delete_terms( 'mesh_template_usage' );

	// Delete all Mesh Settings.
	delete_option( 'mesh_settings' );
	delete_option( 'mesh_post_types' );
	delete_option( 'mesh_version' );
	delete_option( 'mesh_activation' );
}