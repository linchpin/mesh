<?php
/**
 * Class to handle all ajax related class within the admin
 *
 * @since 1.0
 * @package LinchpinMultipleContentSections
 * @subpackage AJAX
 */

/**
 * Multiple_Content_Sections_AJAX class.
 */
class Multiple_Content_Sections_AJAX {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		add_action( 'wp_ajax_mcs_add_section',           array( $this, 'mcs_add_section' ) );
		add_action( 'wp_ajax_mcs_choose_layout',         array( $this, 'mcs_choose_layout' ) );
		add_action( 'wp_ajax_mcs_remove_section',        array( $this, 'mcs_remove_section' ) );
		add_action( 'wp_ajax_mcs_update_order',          array( $this, 'mcs_update_order' ) );
		add_action( 'wp_ajax_mcs_update_featured_image', array( $this, 'mcs_update_featured_image' ) );
	}

	/**
	 * Return the markup for a new section.
	 *
	 * @access public
	 * @return void
	 */
	function mcs_add_section() {
		check_ajax_referer( 'mcs_add_section_nonce', 'mcs_add_section_nonce' );

		$post_id = (int) $_POST['mcs_post_id'];

		if ( empty( $post_id ) ) {
			wp_die( -1 );
		}

		$args = array(
			'post_type' => 'mcs_section',
			'post_title' => 'No Title',
			'post_status' => 'draft',
			'post_parent' => $post_id,
		);

		if ( $new_section = wp_insert_post( $args ) ) {
			$section = get_post( $new_section );
			mcs_add_section_admin_markup( $section );
			wp_die();
		} else {
			wp_die( -1 );
		}

		wp_die();
	}

	/**
	 * Select a section. Return the template using AJAX
	 *
	 * @since 1.2.0
	 */
	function mcs_choose_layout() {
		check_ajax_referer( 'mcs_choose_layout_nonce', 'mcs_choose_layout_nonce' );

		if( ! $section_layout = sanitize_title( $_POST['mcs_section_layout'] ) ) {
			$section_layout = 'default';
		}

		$section_ID = (int) $_POST['mcs_section_id'];

		if ( ! current_user_can( 'edit_post', $section_ID ) ) {
			return;
		}

		$post= get_post( $section_ID );

		$section_post_content = $post->post_content;

		include LINCHPIN_MCS___PLUGIN_DIR . 'admin/templates/' . $section_layout . '.php';

		wp_die();
	}

	/**
	 * Remove the selected section from
	 *
	 * @since 1.0
	 */
	function mcs_remove_section() {
		check_ajax_referer( 'mcs_remove_section_nonce', 'mcs_remove_section_nonce' );

		$post_id    = (int) $_POST['mcs_post_id'];
		$section_id = (int) $_POST['mcs_section_id'];

		if ( empty( $post_id ) || empty( $section_id ) ) {
			wp_die( -1 );
		}

		if ( ! $section = get_post( $section_id ) ) {
			wp_die( -1 );
		}

		if ( $post_id != $section->post_parent ) {
			wp_die( -1 );
		}

		if ( wp_trash_post( $section_id ) ) {
			wp_die( 1 );
		} else {
			wp_die( -1 );
		}
	}

	/**
	 * Save the update order of sections after drag and drop reordering
	 *
	 * @since 1.0
	 */
	function mcs_update_order() {
		check_ajax_referer( 'mcs_reorder_section_nonce', 'mcs_reorder_section_nonce' );

		$post_id     = (int) $_POST['mcs_post_id'];
		$section_ids = array_map( 'intval', $_POST['mcs_section_ids'] );

		if ( empty( $post_id ) || empty( $section_ids ) ) {
			wp_die( -1 );
		}

		foreach ( $section_ids as $key => $section_id ) {
			$section = get_post( $section_id );

			if ( empty( $section ) ) {
				continue;
			}

			if ( $section->post_parent !== $post_id ) {
				continue;
			}

			$post_args = array(
				'ID' => $section_id,
				'menu_order' => $key,
			);

			wp_update_post( $post_args );
		}

		wp_die();
	}

	/**
	 * Update the sections featured image.
	 *
	 * @since 1.0
	 * @access public
	 * @return void
	 */
	function mcs_update_featured_image() {
		check_ajax_referer( 'mcs_featured_image_nonce', 'mcs_featured_image_nonce' );

		$post_id = (int) $_POST['mcs_section_id'];
		$image_id = (int) $_POST['mcs_image_id'];

		if ( 'attachment' !== get_post_type( $image_id ) ) {
			wp_die( -1 );
		}

		if ( 'mcs_section' !== get_post_type( $post_id ) ) {
			wp_die( -1 );
		}

		update_post_meta( $post_id, '_thumbnail_id', $image_id );

		wp_die();
	}
}

$multiple_content_sections_ajax = new Multiple_Content_Sections_AJAX();
