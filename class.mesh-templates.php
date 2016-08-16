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
		add_action( 'admin_init', array( $this, 'add_mesh_template_description' ) );
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
			'supports' => array( 'title', 'excerpt' ),
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
	 * Utilize the excerpt to give a template a description
	 *
	 * @since 1.1.0
	 */
	function add_mesh_template_description() {
		remove_meta_box( 'postexcerpt', 'mesh_template', 'side' );
		add_meta_box( 'postexcerpt', __( 'Template Description' ), 'post_excerpt_meta_box', 'mesh_template', 'normal', 'high' );
	}

	/**
	 * Add Mesh Template Menu Item
	 */
	public static function add_mesh_menu() {
		add_submenu_page( 'mesh', __( 'View Templates', 'mesh' ) , __( 'View Templates', 'mesh' ), 'edit_posts', 'edit.php?post_type=mesh_template' );
		add_submenu_page( 'mesh', __( 'Add Template', 'mesh' ), __( 'Add Template', 'mesh' ), 'edit_posts', 'post-new.php?post_type=mesh_template' );
	}
}

$mesh_templates = new Mesh_Templates();
