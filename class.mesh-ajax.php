<?php
/**
 * Class to handle all ajax related class within the admin
 *
 * @since      1.0.0
 * @package    Mesh
 * @subpackage AJAX
 */

/**
 * Mesh_AJAX class.
 */
class Mesh_AJAX {

	/**
	 * __construct function.
	 *
	 * @access public
	 */
	function __construct() {
		add_action( 'wp_ajax_mesh_add_section',           array( $this, 'mesh_add_section' ) );
		add_action( 'wp_ajax_mesh_save_section',          array( $this, 'mesh_save_section' ) );
		add_action( 'wp_ajax_mesh_remove_section',        array( $this, 'mesh_remove_section' ) );
		add_action( 'wp_ajax_mesh_choose_layout',         array( $this, 'mesh_choose_layout' ) );
		add_action( 'wp_ajax_mesh_update_order',          array( $this, 'mesh_update_order' ) );
		add_action( 'wp_ajax_mesh_update_featured_image', array( $this, 'mesh_update_featured_image' ) );
		add_action( 'wp_ajax_mesh_dismiss_notification',  array( $this, 'mesh_dismiss_notification' ) );

		/**
		 * Since 1.1
		 */
		add_action( 'wp_ajax_mesh_trash_hidden_blocks',          array( $this, 'mesh_trash_hidden_blocks' ) );
	}

	/**
	 * Return the markup for a new section.
	 *
	 * @access public
	 * @return void
	 */
	function mesh_add_section() {
		check_ajax_referer( 'mesh_add_section_nonce', 'mesh_add_section_nonce' );

		$post_id = 0;
		$menu_order = 0;

		if ( ! isset( $_POST['mesh_post_id'] ) ) { // Input var okay.
			wp_die( -1 );
		} else {
			$post_id = intval( $_POST['mesh_post_id'] ); // Input var okay.
		}

		if ( isset( $_POST['mesh_section_count'] ) ) { // Input var okay.
			$menu_order = absint( $_POST['mesh_section_count'] ); // Input var okay.
		}

		$section_args = array(
			'post_type'   => 'mesh_section',
			'post_title'  => esc_html__( 'No Section Title', 'mesh' ),
			'post_status' => 'draft',
			'post_parent' => $post_id,
			'menu_order'  => $menu_order,
		);

		$new_section = wp_insert_post( $section_args );

		if ( ! empty( $new_section ) ) {
			$section = get_post( $new_section );

			// Make sure the new section has one block (default number needed).
			mesh_maybe_create_section_blocks( $section, 1 );

			mesh_add_section_admin_markup( $section );
			wp_die();
		} else {
			wp_die( -1 );
		}
	}

	/**
	 * Save a block via AJAX
	 *
	 * @since 1.0
	 */
	function mesh_save_section() {
		check_ajax_referer( 'mesh_save_section_nonce', 'mesh_save_section_nonce' );

		if ( ! isset( $_POST['mesh_section_id'] ) ) { // Input var ok.
			wp_die( -1 );
		}

		$section_id = intval( wp_unslash( $_POST['mesh_section_id'] ) ); // Input var ok.

		$section = get_post( $section_id );

		if ( empty( $section ) ) {
			wp_die( -1 );
		}

		if ( ! isset( $_POST['mesh_section_data'] ) ) { // WPCS: input var okay.
			wp_die( -1 );
		}

		$mesh_section_data = wp_unslash( $_POST['mesh_section_data'] ); // WPCS: input var okay.

		parse_str( $mesh_section_data, $passed_args );

		$section_data = $passed_args['mesh-sections'][ $section->ID ];

		// Only apply if the filter hasn't been removed.
		if ( has_filter( 'wpautop' ) ) {
			$section_data['post_content'] = wpautop( $section_data['post_content'] );
		}

		// Only need certain arguments to be passed on.
		$new_data = array(
			'action' => $passed_args['action'],
			'mesh_action' => 'mesh_save_section',
			'mesh_content_sections_nonce' => $passed_args['mesh_content_sections_nonce'],
			'mesh-sections' => array(
				$section->ID => $section_data,
			),
		);

		$_POST = array_merge( $_POST, $new_data ); // WPCS: input var okay.

		$section_args = array(
			'ID'         => $section->ID,
			'post_title' => sanitize_text_field( wp_unslash( $_POST['mesh-sections'][ $section->ID ]['post_title'] ) ), // WPCS: input var okay.
			'menu_order' => intval( wp_unslash( $_POST['mesh-sections'][ $section->ID ]['menu_order'] ) ), // WPCS: input var okay.
		);

		wp_update_post( $section_args );
	}

	/**
	 * Select a section. Return the template using AJAX
	 *
	 * @since 0.2.0
	 */
	function mesh_choose_layout() {
		check_ajax_referer( 'mesh_choose_layout_nonce', 'mesh_choose_layout_nonce' );

		if ( isset( $_POST['mesh_section_id'] ) ) { // Input var okay.
			$section_id = intval( $_POST['mesh_section_id'] ); // Input var okay.
		}

		if ( empty( $section_id ) || ! current_user_can( 'edit_post', $section_id ) ) {
			wp_die();
		}

		if ( isset( $_POST['mesh_section_layout'] ) ) { // Input var okay.
			$selected_template = sanitize_text_field( wp_unslash( $_POST['mesh_section_layout'] ) ); // Input var okay.
		} else {
			$selected_template = 'mesh-columns-1.php';
		}

		$section = get_post( $section_id );

		if ( empty( $section ) ) {
			wp_die( -1 );
		}

		$block_template = mesh_locate_template_files();

		$templates = apply_filters( 'mesh_section_data', $block_template );

		// Make sure that a section has enough blocks to fill the template.
		$block_count       = $templates[ $selected_template ]['blocks'];

		$blocks = mesh_cleanup_section_blocks( $section, $block_count );

		// Reset our widths on layout change.
		foreach ( $blocks as $block ) {
			delete_post_meta( $block->ID, '_mesh_column_width' );
		}

		ob_start();

		/*
		 * This block count is determined by the selected template above.
		 * It's important to pass this to the admin to control if a
		 * section's blocks have a post_status of publish or draft.
		 */
		include( LINCHPIN_MESH___PLUGIN_DIR . '/admin/section-inside.php' );
		$output = ob_get_contents();

		ob_end_clean();

		// Clean whitespace before output to prevent jQuery ajax warnings.
		echo trim( $output ); // WPCS: XSS ok, sanitization ok.

		wp_die();
	}

	/**
	 * Trash blocks that are not visible due to column selection.
	 *
	 * @since 1.1
	 */
	function mesh_trash_hidden_blocks() {
		check_ajax_referer( 'mesh_choose_layout_nonce', 'mesh_choose_layout_nonce' );

		if ( isset( $_POST['mesh_section_id'] ) ) { // WPCS: Input var okay.
			$section_id = absint( $_POST['mesh_section_id'] ); // WPCS: Input var okay.
		} else {
			wp_die( -1 );
		}

		if ( ! current_user_can( 'edit_post', $section_id ) ) {
			wp_die( -1 );
		}

		if ( ! isset( $_POST['mesh_section_data'] ) ) { // WPCS: Input var okay.
			wp_die( -1 );
		}

		// Save our section before we trash anything.
		$this->mesh_save_section();

		$selected_template = get_post_meta( $section_id, '_mesh_template', true );

		$templates     = mesh_locate_template_files();
		$number_needed = $templates[ $selected_template ]['blocks'];
		$blocks        = mesh_get_section_blocks( $section_id, array(
			'publish',
			'draft',
		) );

		if ( empty( $blocks ) ) {
			wp_die( -1 );
		}

		$count = count( $blocks );

		if ( $count > $number_needed ) {
			$total = $count;

			while ( $total > $number_needed ) {
				wp_delete_post( $blocks[ $total - 1 ]->ID );
				$total--;
			}

			wp_die( 1 );
		}

		wp_die( 0 );
	}

	/**
	 * Remove the selected section from
	 *
	 * @since 1.0
	 */
	function mesh_remove_section() {
		check_ajax_referer( 'mesh_remove_section_nonce', 'mesh_remove_section_nonce' );

		$post_id    = (int) intval( wp_unslash( $_POST['mesh_post_id'] ) ); // Input var okay. WPCS: Sanitization ok.
		$section_id = (int) intval( wp_unslash( $_POST['mesh_section_id'] ) ); // Input var okay. WPCS: Sanitization ok.

		if ( empty( $post_id ) || empty( $section_id ) ) {
			wp_die( -1 );
		}

		$section = get_post( $section_id );

		if ( empty( $section ) ) {
			wp_die( -1 );
		}

		if ( $post_id !== $section->post_parent ) {
			wp_die( -1 );
		}

		if ( wp_trash_post( $section_id ) ) {
			// Trash the section's blocks.
			foreach ( mesh_get_section_blocks( $section_id ) as $block ) {
				if ( $section_id === $block->post_parent ) {
					wp_trash_post( $block->ID );
				}
			}

			$statuses = array( 'publish', 'draft' );

			$sections = mesh_get_sections( $post_id, 'array', $statuses );

			// If we don't have any sections remaining. Show the initial set.
			if ( empty( $sections ) ) {

				// Clear Post terms related to templates.
				wp_set_object_terms( $post_id, null, 'mesh_template_types' );
				wp_set_object_terms( $post_id, null, 'mesh_template_usage' );

				include LINCHPIN_MESH___PLUGIN_DIR . 'admin/sections-empty.php';
				exit;
			} else {
				wp_die( 1 );
			}
		} else {
			wp_die( -1 );
		}
	}

	/**
	 * Save the order of sections after drag and drop reordering.
	 *
	 * @since 1.0
	 */
	function mesh_update_order() {
		check_ajax_referer( 'mesh_reorder_section_nonce', 'mesh_reorder_section_nonce' );

		$post_id     = (int) $_POST['mesh_post_id']; // WPCS: XSS ok, sanitization ok.
		$section_ids = array_values( array_map( 'intval', $_POST['mesh_section_ids'] ) ); // WPCS: XSS ok, sanitization ok.

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
	function mesh_update_featured_image() {
		check_ajax_referer( 'mesh_featured_image_nonce', 'mesh_featured_image_nonce' );

		$post_id  = (int) $_POST['mesh_section_id']; // WPCS: input var okay.

		if ( ! isset( $_POST['mesh_image_id'] ) || 0 === (int) $_POST['mesh_image_id'] ) {
			die( 0 );
		}

		$image_id = (int) $_POST['mesh_image_id']; // WPCS: input var okay.

		if ( 'mesh_section' !== get_post_type( $post_id ) ) {
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
	 *
	 * @since 1.0
	 */
	function mesh_dismiss_notification() {
		check_ajax_referer( 'mesh_dismiss_notification_nonce', 'mesh_dismiss_notification_nonce' );

		$user_id = get_current_user_id();

		if ( ! isset( $_POST['mesh_notification_type'] ) || empty( $_POST['mesh_notification_type'] ) ) { // Input var okay.
			return;
		}

		$notification_type = sanitize_title( wp_unslash( $_POST['mesh_notification_type'] ) ); // Input var okay.

		$notifications = maybe_unserialize( get_user_option( 'linchpin_mesh_notifications', $user_id ) );

		if ( empty( $notifications ) ) {
			$notifications = array();
		}

		$notifications[ $notification_type ] = '1';

		if ( current_user_can( 'edit_posts' ) ) {
			update_user_meta( $user_id, 'linchpin_mesh_notifications', $notifications );
			wp_die( 1 );
		}
	}
}

$mesh_ajax = new Mesh_AJAX();
