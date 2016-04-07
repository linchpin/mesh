<?php
/**
 * Class to handle all ajax related class within the admin
 *
 * @since      1.0.0
 * @package    Mesh
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
		add_action( 'wp_ajax_mcs_save_section',          array( $this, 'mcs_save_section' ) );
		add_action( 'wp_ajax_mcs_choose_layout',         array( $this, 'mcs_choose_layout' ) );
		add_action( 'wp_ajax_mcs_remove_section',        array( $this, 'mcs_remove_section' ) );
		add_action( 'wp_ajax_mcs_update_order',          array( $this, 'mcs_update_order' ) );
		add_action( 'wp_ajax_mcs_update_featured_image', array( $this, 'mcs_update_featured_image' ) );
		add_action( 'wp_ajax_mcs_dismiss_notification',  array( $this, 'mcs_dismiss_notification' ) );
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
		$menu_order = (int) $_POST['mcs_section_count'];

		if ( empty( $post_id ) ) {
			wp_die( -1 );
		}

		$args = array(
			'post_type'   => 'mcs_section',
			'post_title'  => __( 'No Section Title', 'linchpin-mcs' ),
			'post_status' => 'draft',
			'post_parent' => $post_id,
			'menu_order'  => $menu_order,
		);

		if ( $new_section = wp_insert_post( $args ) ) {
			$section = get_post( $new_section );

			// Make sure the new section has one block (default number needed).
			mcs_maybe_create_section_blocks( $section, 1 );

			mcs_add_section_admin_markup( $section );
			wp_die();
		} else {
			wp_die( -1 );
		}

		wp_die();
	}

	/**
	 * Save a block via AJAX
	 */
	function mcs_save_section() {
		check_ajax_referer( 'mcs_save_section_nonce', 'mcs_save_section_nonce' );

		$section_id = (int) $_POST['mcs_section_id'];

		if ( ! $section = get_post( $section_id ) ) {
			wp_die( -1 );
		}

		parse_str( $_POST['mcs_section_data'], $passed_args );

		// Only need certain arguments to be passed on.
		$new_data = array(
			'action' => $passed_args['action'],
			'mcs_content_sections_nonce' => $passed_args['mcs_content_sections_nonce'],
			'mcs-sections' => array(
				$section->ID => $passed_args['mcs-sections'][ $section->ID ],
			),
		);

		$_POST = array_merge( $_POST, $new_data );

		$section_args = array(
			'ID' => $section->ID,
			'post_title' => $_POST['mcs-sections'][ $section->ID ]['post_title'],
		);

		wp_update_post( $section_args );
	}
	
	/**
	 * Select a section. Return the template using AJAX
	 *
	 * @since 1.2.0
	 */
	function mcs_choose_layout() {
		check_ajax_referer( 'mcs_choose_layout_nonce', 'mcs_choose_layout_nonce' );

		if ( ! $selected_template = sanitize_text_field( $_POST['mcs_section_layout'] ) ) {
			$selected_template = 'mcs-columns-1.php';
		}

		$section_id = (int) $_POST['mcs_section_id'];

		if ( empty( $section_id ) || ! current_user_can( 'edit_post', $section_id ) ) {
			wp_die();
		}

		$section = get_post( $section_id );

		if ( empty( $section ) ) {
			wp_die();
		}

		update_post_meta( $section_id, '_mcs_template', $selected_template );

		$block_template = mcs_locate_template_files();

		$templates = apply_filters( 'mcs_section_data', $block_template );

		// Make sure that a section has enough blocks to fill the template.
		$blocks = mcs_maybe_create_section_blocks( $section, $templates[ $selected_template ]['blocks'] );

		// Reset our widths on layout change.
		foreach ( $blocks as $block ) {
			delete_post_meta( $block->ID, '_mcs_column_width' );
		}
		ob_start();
		include( LINCHPIN_MCS___PLUGIN_DIR . '/admin/section-template-reordering.php' );
		include( LINCHPIN_MCS___PLUGIN_DIR . '/admin/section-blocks.php' );
		include( LINCHPIN_MCS___PLUGIN_DIR . '/admin/section-template-warnings.php' );
		$output = ob_get_contents();

		ob_end_clean();

		// Clean whitespace before output to prevent jQuery ajax warnings.
		echo trim( $output );

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
			//trash the section's blocks
			foreach ( mcs_get_section_blocks( $section_id ) as $block ) {
				if ( $section_id == $block->post_parent ) {
					wp_trash_post( $block->ID );
				}
			}

			wp_die( 1 );
		} else {
			wp_die( -1 );
		}
	}

	/**
	 * Save the order of sections after drag and drop reordering
	 *
	 * @since 1.0
	 */
	function mcs_update_order() {
		check_ajax_referer( 'mcs_reorder_section_nonce', 'mcs_reorder_section_nonce' );

		$post_id     = (int) $_POST['mcs_post_id'];
		$section_ids = array_values( array_map( 'intval', $_POST['mcs_section_ids'] ) );

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

		$post_id  = (int) $_POST['mcs_section_id']; // WPCS: input var okay.
		$image_id = (int) $_POST['mcs_image_id']; // WPCS: input var okay.

		if ( 'mcs_section' !== get_post_type( $post_id ) ) {
			wp_die( -1 );
		}

		if ( empty( $image_id ) ) {
			delete_post_meta( $post_id, '_thumbnail_id' );

			die( 1 );
		}

		if ( 'attachment' !== get_post_type( $image_id ) ) {
			wp_die( -1 );
		}

		update_post_meta( $post_id, '_thumbnail_id', $image_id );

		wp_die( 1 );
	}

	/**
	 * Add the ability to store when notifications are dismissed
	 */
	function mcs_dismiss_notification() {
		check_ajax_referer( 'mcs_dismiss_notification_nonce', 'mcs_dismiss_notification_nonce' );

		$user_id = get_current_user_id();

		$notification_type = sanitize_title( wp_unslash( $_POST['mcs_notification_type'] ) );  // WPCS: input var okay.

		$notifications = maybe_unserialize( get_user_option( 'linchpin_mcs_notifications', $user_id ) );

		if ( empty( $notifications ) ) {
			$notifications = array();
		}

		$notifications[ $notification_type ] = '1';

		if ( current_user_can( 'edit_posts' ) ) {
			update_user_meta( $user_id, 'linchpin_mcs_notifications', $notifications );
			wp_die( 1 );
		}
	}
}

$multiple_content_sections_ajax = new Multiple_Content_Sections_AJAX();
