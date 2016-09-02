<?php
/**
 * Class to handle all ajax related class within the admin
 *
 * @since      1.0.0
 * @package    Mesh
 * @subpackage Template_AJAX
 */

/**
 * Mesh_Templates_AJAX class.
 */
class Mesh_Templates_AJAX {

	/**
	 * __construct function.
	 *
	 * @access public
	 */
	function __construct() {
		add_action( 'wp_ajax_mesh_list_templates',       array( $this, 'list_templates' ) );
		add_action( 'wp_ajax_mesh_choose_template',      array( $this, 'choose_template' ) );

		add_action( 'wp_ajax_mesh_remove_template',      array( $this, 'remove_template' ) );
		add_action( 'wp_ajax_mesh_change_template_type', array( $this, 'change_template_type' ) );

		include_once LINCHPIN_MESH___PLUGIN_DIR . '/class.mesh-templates-duplicate.php';
	}

	/**
	 * Select a template from the mesh_template post type
	 * This template will be used to create all the mesh_sections
	 * on your selected post.
	 *
	 * @since 1.1
	 */
	function list_templates() {

		check_ajax_referer( 'mesh_choose_template_nonce', 'mesh_choose_template_nonce' );

		if ( ! current_user_can( 'edit_post', (int) $_POST['mesh_post_id'] ) ) {
			wp_die();
		}

		$mesh_templates = new WP_Query( array(
			'post_type'      => 'mesh_template',
			'posts_per_page' => apply_filters( 'mesh_templates_per_page', 50 ),
			'no_found_rows'  => true,
			'post_status'    => 'publish',
		) );

		$mesh_template_selectable = true;
		$default_template = false;
		if ( $mesh_templates->have_posts() ) {
			while ( $mesh_templates->have_posts() ) {
				global $post;

				$mesh_templates->the_post();

				$mesh_template_title = get_the_title( $post->ID );
				$mesh_template_id = $post->ID;

				$layout = get_post_meta( $post->ID, '_mesh_template_layout', true );

				// If our layout doesn't have any published sections we should skip it's display.
				if ( empty( $layout ) ) {
					continue;
				}

				include LINCHPIN_MESH___PLUGIN_DIR . 'admin/template-layout-preview.php';
			}

			$mesh_template_title = __( 'Blank Template', 'mesh' );
			$mesh_template_id    = 'blank';
			$layout              = array();
			$layout['row-blank']['blocks'][] = array(
				'columns' => 12,
				'offset' => 0,
			);
			$default_template = true;
			include LINCHPIN_MESH___PLUGIN_DIR . 'admin/template-layout-preview.php';
		} else {
			esc_html_e( 'No Templates Found. Did you build one yet?', 'mesh' );
		} ?>
		<p>
			<a href="#" class="button primary mesh-template-start dashicons-before dashicons-plus"><?php esc_html_e( 'Select Template', 'mesh' ); ?></a>
			<a href="#" class="button primary mesh-template-skip dashicons-before dashicons-plus"><?php esc_html_e( 'Nevermind Start from Scratch', 'mesh' ); ?></a>
		</p>
		<?php

		include LINCHPIN_MESH___PLUGIN_DIR . 'admin/template-layout-usage.php';

		wp_die();
	}

	/**
	 * Choose a template and build out all mesh_section posts
	 * based on the selected template.
	 *
	 * @todo There should probably be a busy spinner of sorts while
	 *       the build out is being done behind the scenes.
	 *
	 * @since 1.1
	 */
	function choose_template() {
		check_ajax_referer( 'mesh_choose_template_nonce', 'mesh_choose_template_nonce' );

		$post_id            = ( isset( $_POST['mesh_post_id'] ) && '' !== $_POST['mesh_post_id'] ) ? (int) $_POST['mesh_post_id'] : 0;
		$mesh_template_id   = ( isset( $_POST['mesh_template_id'] ) ) ? (int) $_POST['mesh_template_id'] : 0;
		$mesh_template_type = ( isset( $_POST['mesh_template_type'] ) ) ? sanitize_title( $_POST['mesh_template_type'] ) : '';

		if ( ! current_user_can( 'edit_post', $mesh_template_id ) || empty( $post_id ) || empty( $mesh_template_id ) ) {
			wp_die( -1 );
		}

		if ( $mesh_template = get_post( $mesh_template_id ) ) {
			// Apply template type to our taxonomy that tracks template usage.
			wp_set_object_terms( $post_id, array( $mesh_template->post_name ), 'mesh_template_usage', false );

			$template_terms = get_terms( array(
				'taxonomy' => 'mesh_template_types',
				'hide_empty' => false,
				'fields' => 'id=>slug',
			) );

			if ( in_array( $mesh_template_type, $template_terms, true ) ) {
				wp_set_object_terms( $post_id, $mesh_template_type, 'mesh_template_types', false );
			}

			$mesh_templates_duplicate = new Mesh_Templates_Duplicate();

			$duplicate_sections = $mesh_templates_duplicate->duplicate_sections( $mesh_template_id, $post_id );

			if ( ! empty( $duplicate_sections ) ) {
				echo wp_kses( $duplicate_sections, Mesh::get_admin_template_kses() );
			} else {
				echo 'did not duplicate sections';
			}
			exit;
		}

		exit;
	}

    /**
	 * This should delete the entire child set of Mesh Sections.
     *
     * @since 1.1
     */
	function remove_template() {
		check_ajax_referer( 'mesh_choose_template_nonce', 'mesh_choose_template_nonce' );

		$post_id = ( isset( $_POST['mesh_post_id'] ) && '' !== $_POST['mesh_post_id'] ) ? (int) $_POST['mesh_post_id'] : 0;

		$sections = mesh_get_sections( $post_id );
	}

	/**
	 * Remove "Reference" term from template's parent Post.
     * This allows for the mesh section layout, order and sizing to be editable.
     *
     * @since 1.1
	 */
	function change_template_type() {
		check_ajax_referer( 'mesh_choose_template_nonce', 'mesh_choose_template_nonce' );

		$post_id = ( isset( $_POST['mesh_post_id'] ) && '' !== $_POST['mesh_post_id'] ) ? (int) $_POST['mesh_post_id'] : 0;

		if ( ! current_user_can( 'edit_post', $post_id ) || empty( $post_id ) ) {
			wp_die( -1 );
		}

		wp_set_object_terms( $post_id, 'starter', 'mesh_template_types', false );

		$current_post = get_post( $post_id );

		if ( ! empty( $current_post ) ) {
			Mesh::edit_page_form( $current_post );
			wp_die();
        }

        wp_die( -1 );
	}
}

$mesh_templates_ajax = new Mesh_Templates_AJAX();
