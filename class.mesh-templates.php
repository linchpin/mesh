<?php
/**
 * Base Class for templating.
 *
 * This class creates a new custom post type that are used to store
 * reusable templates within Mesh
 *
 * @todo Upon save post. Layout changes should be applied to all posts that
 *       use the selected templates as a reference.
 *
 * @package    Mesh
 * @subpackage Templates
 * @since      1.1
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Class Mesh_Templates
 */
class Mesh_Templates {
	/**
	 * Store an instance of our Duplicate Class
	 *
	 * @var Mesh_Templates_Duplicate
	 */
	private $mesh_templates_duplicate;

	/**
	 * Mesh_Templates constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 20, 2 ); // This saving should happen later to make sure our data is available.

		// Columns.
		add_action( 'manage_mesh_template_posts_custom_column', array( $this, 'add_layout_column' ), 10, 2 );
		add_filter( 'manage_mesh_template_posts_columns', array( $this, 'add_layout_columns' ) );

		require_once LINCHPIN_MESH___PLUGIN_DIR . '/class.mesh-templates-duplicate.php';

		add_action( 'load-edit.php', array( $this, 'admin_notices' ) );
	}

	/**
	 * Add our welcome area to mesh templates
	 *
	 * @since 1.1
	 */
	function admin_notices() {

		$screen = get_current_screen();

		// Only edit mesh template list screen.
		if ( 'edit-mesh_template' === $screen->id ) {
			add_action( 'all_admin_notices', array( $this, 'welcome_message' ) );
		}
	}

	/**
	 * Output our welcome markup
	 */
	function welcome_message() {
		include_once LINCHPIN_MESH___PLUGIN_DIR . 'admin/welcome.php';
	}

	/**
	 * Create our mesh_template post type along with
	 * our mesh_template_usage taxonomy.
	 *
	 * @access public
	 * @return void
	 */
	function init() {

		$labels = array(
			'name'                => esc_html_x( 'Mesh Templates', 'Mesh Templates', 'mesh' ),
			'singular_name'       => esc_html_x( 'Mesh Template', 'Mesh Template', 'mesh' ),
			'menu_name'           => esc_html__( 'Mesh', 'mesh' ),
			'name_admin_bar'      => esc_html__( 'Mesh Template', 'mesh' ),
			'parent_item_colon'   => esc_html__( 'Parent Mesh Template:', 'mesh' ),
			'all_items'           => esc_html__( 'Mesh Templates', 'mesh' ),
			'add_new_item'        => esc_html__( 'Add New Mesh Template', 'mesh' ),
			'add_new'             => esc_html__( 'Add Template', 'mesh' ),
			'new_item'            => esc_html__( 'New Mesh Template', 'mesh' ),
			'edit_item'           => esc_html__( 'Edit Mesh Template', 'mesh' ),
			'update_item'         => esc_html__( 'Update Mesh Template', 'mesh' ),
			'view_item'           => esc_html__( 'View Mesh Template', 'mesh' ),
			'search_items'        => esc_html__( 'Search Mesh Templates', 'mesh' ),
			'not_found'           => esc_html__( 'Not found', 'mesh' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'mesh' ),
		);

		register_post_type( 'mesh_template', array(
			'label'               => esc_html__( 'Mesh Template', 'mesh' ),
			'description'         => esc_html__( 'Mesh Template', 'mesh' ),
			'labels'              => $labels,
			'public' => false,
			'hierarchical' => true,
			'supports' => array( 'title', 'editor' ),
			'capability_type' => 'post',
			'has_archive' => false,
			'show_in_menus' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'menu_icon' => 'dashicons-mesh-logo',
			'show_ui' => true,
			'rewrite' => false,
		) );

		// Using an extra variable for the array to support PHP 5.4.
		$mesh_post_types_array = get_option( 'mesh_post_types', array() );
		$mesh_post_types = array();

		if ( ! empty( $mesh_post_types_array ) ) {
			$mesh_post_types = array_keys( $mesh_post_types_array );
		}

		$available_post_types = array_merge( array( 'mesh_template' ), $mesh_post_types );

		register_taxonomy( 'mesh_template_usage', $available_post_types, array(
			'labels' => array(
				'name'              => esc_html_x( 'Mesh Template', 'Mesh Template', 'mesh' ),
				'singular_name'     => esc_html_x( 'Mesh Template', 'Mesh Template', 'mesh' ),
				'search_items'      => esc_html__( 'Search Mesh Template Usage', 'mesh' ),
				'all_items'         => esc_html__( 'All Mesh Template Usage', 'mesh' ),
				'parent_item'       => esc_html__( 'Parent Mesh Template Usage', 'mesh' ),
				'parent_item_colon' => esc_html__( 'Parent Mesh Template Usage:', 'mesh' ),
				'edit_item'         => esc_html__( 'Edit Mesh Template Usage', 'mesh' ),
				'update_item'       => esc_html__( 'Update Mesh Template Usage', 'mesh' ),
				'add_new_item'      => esc_html__( 'Add New Mesh Template Usage', 'mesh' ),
				'new_item_name'     => esc_html__( 'New Mesh Template Usage Name', 'mesh' ),
				'menu_name'         => esc_html__( 'Mesh Template Usage', 'mesh' ),
			),
			'show_ui' => LINCHPIN_MESH_DEBUG_MODE,
			'query_var' => true,
			'rewrite' => false,
			'show_admin_column' => true,
		) );

		register_taxonomy( 'mesh_template_types', $available_post_types, array(
			'labels' => array(
				'name'              => esc_html_x( 'Mesh Template Type', 'Mesh Template Type', 'mesh' ),
				'singular_name'     => esc_html_x( 'Mesh Template Type', 'Mesh Template Type', 'mesh' ),
				'search_items'      => esc_html__( 'Search Mesh Template Types', 'mesh' ),
				'all_items'         => esc_html__( 'All Mesh Template Types', 'mesh' ),
				'parent_item'       => esc_html__( 'Parent Mesh Template Types', 'mesh' ),
				'parent_item_colon' => esc_html__( 'Parent Mesh Template Types:', 'mesh' ),
				'edit_item'         => esc_html__( 'Edit Mesh Template Type', 'mesh' ),
				'update_item'       => esc_html__( 'Update Mesh Template Type', 'mesh' ),
				'add_new_item'      => esc_html__( 'Add New Mesh Template Type', 'mesh' ),
				'new_item_name'     => esc_html__( 'New Mesh Template Type Name', 'mesh' ),
				'menu_name'         => esc_html__( 'Mesh Template Type', 'mesh' ),
			),
			'show_ui' => LINCHPIN_MESH_DEBUG_MODE,
			'rewrite' => false,
			'show_in_nav_menus' => false,
			'show_in_rest' => false,
			'show_admin_column' => false, // @todo this should be added back in, in a later version.
		) );
	}

	/**
	 * Save our layout within simple post meta for easier retrieval later on.
	 *
	 * @access public
	 *
	 * @param mixed  $post_id Current Post ID.
	 * @param object $post    Current Post Object.
	 *
	 * @return void
	 */
	public function save_post( $post_id, $post ) {

		// Skip revisions and autosaves.
		if ( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		// Users should have the ability to edit listings.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( 'mesh_template' !== $post->post_type ) {
			return;
		}

		if ( ! isset( $_POST['mesh_content_sections_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['mesh_content_sections_nonce'] ) ), 'mesh_content_sections_nonce' ) ) { // WPCS: input var okay.
			return;
		}

		if ( empty( $_POST['mesh-sections'] ) ) { // WPCS: input var okay.
			return;
		}

		remove_action( 'save_post', array( $this, 'save_post' ), 10 );

		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			$mesh_layout_post_meta  = get_post_meta( $post_id, '_mesh_template_layout', true );
			$single_mesh_section    = (array) wp_unslash( $_POST['mesh-sections'] ); // WPCS: input var okay, sanitization ok.
			$first_mesh_section_key = key( $single_mesh_section );
			$single_mesh_section    = array_shift( wp_unslash( $_POST['mesh-sections'] ) ); // WPCS: input var okay. sanitization ok.
			$mesh_layout_preview    = $this->update_template_single_section_preview( $first_mesh_section_key, $mesh_layout_post_meta, $single_mesh_section );
		} else {
			$mesh_layout_preview = $this->create_template_preview( wp_unslash( $_POST['mesh-sections'] ) ); // WPCS: input var okay, sanitization ok.
		}

		if ( ! empty( $mesh_layout_preview ) ) {
			update_post_meta( $post_id, '_mesh_template_layout', $mesh_layout_preview );
			wp_insert_term( $post->post_title, 'mesh_template_usage', array(
				'slug' => $post->post_name,
			) );
		} else {
			delete_post_meta( $post_id, '_mesh_template_layout' );
			wp_delete_term( $post->post_title, 'mesh_template_usage', array(
				'slug' => $post->post_name,
			) );
		}

		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}

	/**
	 * On AJAX calls to update sections we still need to update our preview.
	 * This will rebuild the preview but only update the section being passed
	 * in the AJAX call.
	 *
	 * @since 1.1
	 * @param int    $section_id       ID of section.
	 * @param array  $mesh_layout_meta Array of our sections within our template.
	 * @param object $section_data     Single object of our new build.
	 * @return array
	 */
	public function update_template_single_section_preview( $section_id, $mesh_layout_meta, $section_data ) {

		// Process the section's blocks.
		$blocks = array();

		if ( ! empty( $section_data['blocks'] ) ) {
			$blocks = $section_data['blocks'];
		}

		if ( is_array( $mesh_layout_meta[ sanitize_title( 'row-' . $section_id ) ] ) ) {
			$mesh_layout_meta[ sanitize_title( 'row-' . $section_id ) ]['blocks'] = array(); // Reset blocks array.
		} else {
			return;
		}

		foreach ( $blocks as $block_id => $block_data ) {
			$block = get_post( (int) $block_id );

			if ( empty( $block ) || 'publish' !== get_post_status( $block ) || 'mesh_section' !== $block->post_type || $section_id !== $block->post_parent ) {
				continue;
			}

			$offset   = (int) $section_data['blocks'][ intval( $block_id ) ]['offset'];
			$columns  = (int) $section_data['blocks'][ intval( $block_id ) ]['column_width'];
			$centered = (bool) $section_data['blocks'][ intval( $block_id ) ]['centered'];

			$mesh_layout_meta[ sanitize_title( 'row-' . $section_id ) ]['blocks'][] = array(
				'columns'  => $columns - $offset,
				'offset'   => $offset,
				'centered' => $centered,
			);
		}

		return $mesh_layout_meta;
	}

	/**
	 * Create the preview "thumbnail" of our template.
	 *
	 * Used in the post list and the add new section process
	 *
	 * @param array $sections Our sections to build out.
	 *
	 * @since 1.1
	 * @return array
	 */
	private function create_template_preview( $sections ) {

		$count       = 0;
		$mesh_layout = array();

		foreach ( $sections as $section_id => $section_data ) {

			$section = get_post( (int) $section_id );

			if ( 'mesh_section' !== $section->post_type ) {
				continue;
			}

			if ( 'publish' !== get_post_status( $section ) ) {
				continue;
			}

			// Create a new row per section.
			$mesh_layout[ sanitize_title( 'row-' . $section_id ) ] = array();

			$count++;

			// Process the section's blocks.
			$blocks = array();

			if ( ! empty( $section_data['blocks'] ) ) {
				$blocks = $section_data['blocks'];
			}

			foreach ( $blocks as $block_id => $block_data ) {
				$block = get_post( intval( $block_id ) );

				if ( empty( $block ) || 'publish' !== get_post_status( $block ) || 'mesh_section' !== $block->post_type || $section->ID !== $block->post_parent ) {
					continue;
				}

				$offset   = intval( $section_data['blocks'][ sanitize_title( $block_id ) ]['offset'] );
				$columns  = intval( $section_data['blocks'][ sanitize_title( $block_id ) ]['column_width'] );
				$centered = boolval( $section_data['blocks'][ sanitize_title( $block_id ) ]['centered'] );

				$mesh_layout[ sanitize_title( 'row-' . $section_id ) ]['blocks'][] = array(
					'columns'  => $columns - $offset,
					'offset'   => $offset,
					'centered' => $centered,
				);
			}
		}

		return $mesh_layout;
	}

	/**
	 * Add Layout Column Title and also reorder our columns
	 *
	 * @since 1.1
	 * @param int $columns The columns in the admin to iterate through.
	 *
	 * @return mixed
	 */
	public function add_layout_columns( $columns ) {

		foreach ( $columns as $key => $title ) {

			if ( 'title' !== $key ) {
				continue;
			} else {
				unset( $columns['taxonomy-mesh_template_types'] );
				unset( $columns['title'] );
				unset( $columns['taxonomy-mesh_template_usage'] );

				$date = $columns['date'];

				unset( $columns['date'] );
				$columns['layout']                       = esc_html__( 'Layout', 'mesh' );
				$columns['title']                        = $title;
				$columns['mesh_template_uses']           = 'Uses';
				$columns['taxonomy-mesh_template_usage'] = 'Template';
				$columns['date']                         = $date;

				break;
			}
		}

		return $columns;
	}

	/**
	 * Output Layout Column column
	 *
	 * @param string $column  Column Name.
	 * @param int    $post_id Post ID.
	 */
	public function add_layout_column( $column, $post_id ) {

		switch ( $column ) {
			case 'mesh_template_uses':
				$template_post = get_post( $post_id, array(
					'field' => 'slug',
				) );

				$template_usage = get_term_by( 'slug', $template_post->post_name, 'mesh_template_usage' );

				if ( false === $template_usage ) {
					echo esc_html( 0 );
				} else {
					echo esc_html( (int) $template_usage->count );
				}

				break;
			case 'layout':
				$layout = get_post_meta( $post_id, '_mesh_template_layout', true );

				if ( empty( $layout ) ) {
					esc_html_e( 'Template missing published sections', 'mesh' );
					return;
				}

				/**
				 * Include our layout templates
				 */
				include LINCHPIN_MESH___PLUGIN_DIR . 'admin/template-layout-preview.php';
				break;
		}
	}
}

/**
 * Get our available mesh templates
 *
 * @since 1.1
 * @param string $return_type Query or Array.
 * @param array  $statuses    Publish, Draft.
 *
 * @return array|WP_Query
 */
function mesh_get_templates( $return_type = 'array', $statuses = array( 'publish' ) ) {
	$template_query = new WP_Query( array(
		'post_type'     => 'mesh_template',
		'post_status'   => $statuses,
		'post_per_page' => 100,
		'no_found_rows' => true,
		'orderby'       => 'post_title',
		'order'         => 'ASC',
	) );

	switch ( $return_type ) {
		case 'query':
			return $template_query;
		case 'array':
		default:
			return $template_query->posts;
	}
}

$mesh_templates = new Mesh_Templates();
