<?php
/**
 * Base Class for templating.
 *
 * This class creates a new custom post type that are used to store
 * reusable templates within Mesh
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
	 * Mesh_Templates constructor.
	 */
	function __construct() {
		add_action( 'init',       array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_mesh_menu' ) );
		add_action( 'save_post',  array( $this, 'save_post' ), 20, 2 ); // This saving should happen later to make sure our data is available.

		/*
		 * Columns
		 */
		add_action( 'manage_mesh_template_posts_custom_column' , array( $this, 'add_layout_column' ), 10, 2 );
		add_filter( 'manage_mesh_template_posts_columns', array( $this, 'add_layout_column_title' ) );
	}

	/**
	 * Init function.
	 *
	 * @access public
	 * @return void
	 */
	function init() {

		$labels = array(
			'name'                => _x( 'Mesh Templates', 'Mesh Templates', 'mesh' ),
			'singular_name'       => _x( 'Mesh Template', 'Mesh Template', 'mesh' ),
			'menu_name'           => __( 'Mesh Template', 'mesh' ),
			'name_admin_bar'      => __( 'Mesh Template', 'mesh' ),
			'parent_item_colon'   => __( 'Parent Mesh Template:', 'mesh' ),
			'all_items'           => __( 'All Mesh Templates', 'mesh' ),
			'add_new_item'        => __( 'Add New Mesh Template', 'mesh' ),
			'add_new'             => __( 'Add New', 'mesh' ),
			'new_item'            => __( 'New Mesh Template', 'mesh' ),
			'edit_item'           => __( 'Edit Mesh Template', 'mesh' ),
			'update_item'         => __( 'Update Mesh Template', 'mesh' ),
			'view_item'           => __( 'View Mesh Template', 'mesh' ),
			'search_items'        => __( 'Search Mesh Templates', 'mesh' ),
			'not_found'           => __( 'Not found', 'mesh' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'mesh' ),
		);

		register_post_type( 'mesh_template', array(
			'label'               => __( 'Mesh Template', 'mesh' ),
			'description'         => __( 'Mesh Template', 'mesh' ),
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
			'show_ui' => true,
			'rewrite' => false,
		) );
	}

	/**
	 * Add Mesh Template Menu Item
	 */
	public static function add_mesh_menu() {
		add_submenu_page( 'mesh', __( 'View Templates', 'mesh' ) , __( 'View Templates', 'mesh' ), 'edit_posts', 'edit.php?post_type=mesh_template' );
		add_submenu_page( 'mesh', __( 'Add Template', 'mesh' ), __( 'Add Template', 'mesh' ), 'edit_posts', 'post-new.php?post_type=mesh_template' );
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
	function save_post( $post_id, $post ) {

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

		if ( ! isset( $_POST['mesh_content_sections_nonce'] ) || ! wp_verify_nonce( $_POST['mesh_content_sections_nonce'], 'mesh_content_sections_nonce' )  ) {
			return;
		}

		if ( empty( $_POST['mesh-sections'] ) ) {
			return;
		}

		remove_action( 'save_post', array( $this, 'save_post' ), 10 );

		$count = 0;

		$mesh_layout = array();

		foreach ( $_POST['mesh-sections'] as $section_id => $section_data ) {

			$section = get_post( (int) $section_id );

			if ( 'mesh_section' !== $section->post_type ) {
				continue;
			}

			if ( 'publish' !== get_post_status( $section ) ) {
				continue;
			}

			// Create a new row per section
			$mesh_layout[ sanitize_title( 'row-' . $section_id ) ] = array();

			$count ++;

			// Process the section's blocks.
			$blocks = array();

			if ( ! empty( $section_data['blocks'] ) ) {
				$blocks = $section_data['blocks'];
			}

			foreach ( $blocks as $block_id => $block_data ) {
				$block = get_post( (int) $block_id );

				if ( empty( $block ) || 'publish' != get_post_status( $block ) || 'mesh_section' !== $block->post_type || $section->ID !== $block->post_parent ) {
					continue;
				}

				$offset = (int) $section_data['blocks'][ sanitize_title( $block_id ) ]['offset'];
				$columns = (int) $section_data['blocks'][ sanitize_title( $block_id ) ]['columns'];

				$mesh_layout[ sanitize_title( 'row-' . $section_id ) ]['blocks'][] = array(
					'columns' => $columns - $offset,
					'offset' => $offset,
				);
			}
		}

		if ( ! empty( $mesh_layout ) ) {
			update_post_meta( $post_id, '_mesh_template_layout', $mesh_layout );
		} else {
			delete_post_meta( $post_id, '_mesh_template_layout' );
		}

		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
	}

	/**
	 * Add Layout Column Title
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	function add_layout_column_title( $columns ) {

		$columns['layout'] = __( 'Layout', 'mesh' );

		return $columns;
	}

	/**
	 * Output Layout Column column
	 *
	 * @param string $column  Column Name.
	 * @param int    $post_id Post ID.
	 */
	function add_layout_column( $column, $post_id ) {
		switch ( $column ) {

			case 'layout' :

				$layout = get_post_meta( $post_id, '_mesh_template_layout', true );

				if ( empty( $layout ) ) {
					echo '';
					return;
				}

				?>
				<pre><?php print_r( $layout); ?></pre>
				<div class="mesh-template-row" style="max-width:100px">
				<?php foreach ( $layout as $key => $row ) : ?>
					<div class="mcs-row">
						<?php foreach ( $row['blocks'] as $block ) : ?>
							<div class="<?php if ( ! empty( $block['offset'] ) ) : ?>mesh-offset-<?php esc_attr( $block['offset'] ); ?><?php endif; ?> mesh-block mesh-<?php esc_attr( $block['columns'] ); ?> columns" style="min-height:10px; border:1px solid #ff00bb;"></div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
				</div>
				<?php
				break;
		}
	}
}

/**
 * Get our available mesh templates
 *
 * @since 1.1
 * @param string $return_type
 * @param array  $statuses
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
		case 'query' :
			return $template_query;
			break;

		case 'array' :
		default      :
			return $template_query->posts;
			break;
	}
}

$mesh_templates = new Mesh_Templates();
