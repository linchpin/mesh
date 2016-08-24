<?php
/**
 * Handle duplicating our mesh template structure
 *
 * This class will handle the following
 *
 * - Copy Mesh Sections (mesh_section) and child mesh_sections AKA blocks/columns
 *      - Excluding Revisions
 * - Copy post meta for each mesh_section
 *
 * Inspired by https://wordpress.org/plugins/duplicate-post/ for ideas
 *
 * @since      1.1
 * @package    Mesh
 * @subpackage Templates_Duplicate
 */

/**
 * Class Mesh_Templates_Duplicate
 */
class Mesh_Templates_Duplicate {

	/**
	 * Mesh_Templates_Duplicate constructor.
	 */
	function __construct() {

		// Duplicate Post Meta.
		add_action( 'mesh_duplicate_template_section', array( $this, 'duplicate_post_meta' ), 10, 2 );

		// Duplicate Taxonomies.
		add_action( 'mesh_duplicate_template_section', array( $this, 'duplicate_taxonomies' ), 10, 2 );

		// Duplicate Children and Attachments.
		add_action( 'mesh_duplicate_template_section', array( $this, 'duplicate_children' ), 10, 2 );
	}

	/**
	 * Duplicate all the template's sections
	 *
	 * @param int    $template_id Template ID.
	 * @param string $status      Template Publish Status.
	 * @param int    $post_id     Target Post ID.
	 */
	function duplicate_sections( $template_id, $status = 'publish', $post_id ) {

		$template_id = (int) $template_id;

		if ( $template_post = get_post( $template_id ) ) {
			$new_template_id = $this->duplicate_section( $template_post, $status, $post_id );
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

			wp_set_object_terms( $new_id, NULL, 'category' );

			$taxonomies = get_object_taxonomies( $post->post_type );

			foreach ( $taxonomies as $taxonomy ) {

				$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'orderby' => 'term_order' ) );
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
				add_post_meta( $new_id, $meta_key, $meta_value );
			}
		}
	}

	/**
	 * Duplicate the post attachments to the new section
	 * This does not actually duplicate the files.
	 *
	 * @thanks https://plugins.svn.wordpress.org/duplicate-post/
	 *
	 * @param int    $new_id New Post ID.
	 * @param object $post   Original Post Object.
	 */
	function duplicate_children( $new_id, $post ) {

		$children = new WP_Query( array(
			'post_type'      => array( 'mesh_section', 'attachment' ),
			'posts_per_page' => apply_filters( 'mesh_templates_per_page', 50 ),
			'post_status'    => array( 'publish', 'draft' ),
		    'post_parent'    => $post->ID,
			) );

		if ( $children->have_posts() ) {
			while ( $children->have_posts() ) {
				global $post;

				$children->the_post();

				$this->duplicate_section( $post, '', $new_id );
			}
		}
	}

	/**
	 * Duplicate the section
	 *
	 * @param object $post      Post Object.
	 * @param string $status    Post Status.
	 * @param string $parent_id Parent Post ID.
	 *
	 * @return int|void|WP_Error
	 */
	function duplicate_section( $post, $status = '', $parent_id = '' ) {

		// Skip Revisions.
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( 'attachment' !== $post->post_type ) {
			if ( empty( $status ) ) {
				$status = 'draft';
			}
		}

		$new_post = array(
			'menu_order'     => $post->menu_order,
			'post_author'    => $post->post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent'    => $new_post_parent = empty( $parent_id ) ? $post->post_parent : $parent_id,
			'post_status'    => $status, // Always set a published section to draft. Exclude attachments.
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'post_date'      => $post->post_date,
			'post_date_gmt'  => get_gmt_from_date( $post->post_date ),
		);

		$new_post_id = wp_insert_post( $new_post );

		// Create a new slug.

		$post_name = wp_unique_post_slug( $post->post_name, $new_post_id, $status, $post->post_type, $new_post_parent );

		$new_post = array(
			'ID'        => $new_post_id,
			'post_name' => $post_name,
		);

		// Update our new post with the correct slug and information.
		wp_update_post( $new_post );

		do_action( 'mesh_duplicate_template_section', $new_post_id, $post );

		delete_post_meta( $new_post_id, '_mesh_template_original' );
		add_post_meta( $new_post_id, '_mesh_template_original', $post->ID );

		return $new_post_id;
	}
}
