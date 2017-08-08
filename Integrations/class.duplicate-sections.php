<?php
/**
 * Utility integration used by multiple duplication plugins.
 *
 * This integration does not have any toggles or settings.
 *
 * @package    Mesh
 * @subpackage Integrations
 * @since 1.2
 */

namespace Mesh\Integrations;

use \WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Duplicate_Section
 *
 * @package Mesh\Integrations
 */
class Duplicate_Sections {

	/**
	 * Section_Duplicate constructor.
	 */
	function __construct() {}

	/**
	 * Register all actions.
	 */
	function register_actions() {
		// Duplicate Post Meta.
		add_action( 'mesh_duplicate_section', array( $this, 'duplicate_post_meta' ), 10, 2 );

		// Duplicate Taxonomies.
		add_action( 'mesh_duplicate_section', array( $this, 'duplicate_taxonomies' ), 10, 2 );

		// Duplicate Children and Attachments.
		add_action( 'mesh_duplicate_section', array( $this, 'duplicate_children' ), 10, 2 );
	}

	/**
	 * Duplicate all the template's sections
	 *
	 * @param int        $new_post_id    Target Post ID.
	 * @param object|int $source_post    Original Post Or ID.
	 * @param bool       $include_drafts Include drafts or not.
	 */
	function duplicate_sections( $new_post_id, $source_post, $include_drafts = false ) {

		if ( is_int( $source_post ) ) {
			$source_post = get_post( $source_post );

			if ( ! $source_post ) { // If we don't have a post return.
				return;
			}
		}

		$this->duplicate_children( $new_post_id, $source_post, $include_drafts );
	}

	/**
	 * Duplicate the post attachments to the new section
	 * This does not actually duplicate the files.
	 *
	 * @thanks https://plugins.svn.wordpress.org/duplicate-post/
	 *
	 * @param int      $new_parent_post_id New Post ID.
	 * @param \WP_Post $source_post        Source Post Object.
	 * @param bool     $include_drafts     Include Drafts or not.
	 */
	function duplicate_children( $new_parent_post_id, $source_post, $include_drafts = false ) {

		$post_status = array(
			'publish',
		);

		if ( false !== $include_drafts ) {
			$post_status[] = 'draft';
		}

		$source_post_children = new WP_Query( array(
			'post_type'      => array( 'mesh_section', 'attachment' ),
			'posts_per_page' => 100,
			'post_status'    => $post_status,
			'post_parent'    => $source_post->ID,
			'order_by'       => 'menu_order',
			'order'          => 'ASC',
		) );

		if ( $source_post_children->have_posts() ) {

			$children = $source_post_children->posts;

			foreach ( $children as $child ) {
				$this->duplicate_section( $new_parent_post_id, $child );
			}
		}
	}

	/**
	 * Duplicate the section
	 *
	 * @param int|string $new_parent_post_id Parent Post ID.
	 * @param \WP_Post   $post            Post Object.
	 *
	 * @return int|mixed|/WP_Error
	 */
	function duplicate_section( $new_parent_post_id = '', $post ) {

		// Skip Revisions.
		if ( wp_is_post_revision( $post ) || 'revision' === $post->post_type ) {
			return '';
		}

		$status = 'draft';

		if ( 'attachment' !== $post->post_type ) {
			if ( empty( $status ) ) {
				$status = 'draft';
			}
		} else {
			$status = 'publish';
		}

		$new_date = current_time( 'Y-m-d H:i:s' );

		if ( empty( $new_parent_post_id ) ) {
			$new_post_parent_id = $post->post_parent;
		}

		$new_post = array(
			'menu_order'     => $post->menu_order,
			'post_author'    => $post->post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent'    => $new_parent_post_id,
			'post_status'    => $status, // Always set a published section to draft. Exclude attachments.
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'post_date'      => $new_date,
			'post_date_gmt'  => get_gmt_from_date( $new_date ),
		);

		$new_post_id = wp_insert_post( $new_post );

		// Create a new slug.
		$post_name = wp_unique_post_slug( $post->post_name, $new_post_id, $status, $post->post_type, $new_parent_post_id );

		$new_post = array(
			'ID'        => $new_post_id,
			'post_name' => $post_name,
		);

		// Update our new post with the correct slug and information.
		wp_update_post( $new_post );

		do_action( 'mesh_duplicate_section', $new_post_id, $post );

		$parent_post_type = get_post_type( $post->post_parent );

		if ( $post->post_parent && 'mesh_section' !== $parent_post_type ) {
			return $new_post_id;
		} else {
			return '';
		}
	}

	/**
	 * Copy the taxonomies of a post to another post
	 *
	 * @param int    $new_id New Post ID.
	 * @param object $post   Post Object.
	 */
	function duplicate_taxonomies( $new_id, $post ) {
		global $wpdb;

		if ( isset( $wpdb->terms ) ) {

			wp_set_object_terms( $new_id, null, 'category' );

			$taxonomies = get_object_taxonomies( $post->post_type );

			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array(
					'orderby' => 'term_order',
				) );
				$terms = array();
				$term_length = count( $post_terms );

				for ( $i = 0; $i < $term_length; $i++ ) {
					$terms[] = $post_terms[ $i ]->slug;
				}

				wp_set_object_terms( $new_id, $terms, $taxonomy );
			}
		}
	}

	/**
	 * Duplicate the post meta to the new section
	 *
	 * @param int    $new_id New Post ID.
	 * @param object $post   Original Post Object.
	 */
	function duplicate_post_meta( $new_id, $post ) {
		$post_meta_keys = get_post_custom_keys( $post->ID );

		if ( empty( $post_meta_keys ) ) {
			return;
		}

		foreach ( $post_meta_keys as $meta_key ) {

			$meta_values = get_post_custom_values( $meta_key, $post->ID );

			foreach ( $meta_values as $meta_value ) {
				$meta_value = maybe_unserialize( $meta_value );

				update_post_meta( $new_id, $meta_key, $meta_value );
			}
		}
	}
}
$mesh_duplicate_sections = new Duplicate_Sections();
$mesh_duplicate_sections->register_actions();
