<?php
/**
 * Plugin Name: Mesh
 * Plugin URI: https://meshplugin.com?utm_source=mesh&utm_medium=plugin-admin-page&utm_campaign=wp-plugin
 * Description: Adds multiple sections for content on a post by post basis. Mesh also has settings to enable it for specific post types
 * Version: 1.4.1
 * Text Domain: mesh
 * Domain Path: /languages
 * Author: Linchpin
 * Author URI: https://linchpin.com/?utm_source=mesh&utm_medium=plugin-admin-page&utm_campaign=wp-plugin
 * License: GPLv2 or later
 *
 * @package Mesh
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

/**
 * Define all globals.
 */
define( 'LINCHPIN_MESH_VERSION', '1.4.1' );
define( 'LINCHPIN_MESH_PLUGIN_NAME', esc_html__( 'Mesh', 'mesh' ) );
define( 'LINCHPIN_MESH__MINIMUM_WP_VERSION', '4.0' );
define( 'LINCHPIN_MESH___PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LINCHPIN_MESH___PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Debug will set show_ui to true on most post types that are typically hidden.
 *
 * @since 1.1
 */
define( 'LINCHPIN_MESH_DEBUG_MODE', false );

$GLOBALS['mesh_current_version'] = get_option( 'mesh_version', '0.0' ); // Get our current Mesh Version.

require_once 'includes/utilities.php';

require_once 'class.mesh-settings.php';
require_once 'class.mesh-templates.php';
require_once 'class.mesh-pointers.php';
require_once 'class.mesh.php';
require_once 'class.mesh-reponsive-grid.php';
require_once 'class.mesh-controls.php';
require_once 'class.mesh-input.php';
require_once 'class.mesh-upgrades.php';
require_once 'class.mesh-integrations.php';
require_once 'class.mesh-common.php';

if ( is_admin() ) {
	require_once 'class.mesh-install.php';
}

$mesh = new Mesh();

if ( is_admin() ) {
	$mesh_pointers = new Mesh_Admin_Pointers();
}

add_action( 'init', array( 'Mesh_Settings', 'init' ) );

/**
 * Flush rewrite rules when the plugin is activated.
 */
function mesh_activation_hook() {
	add_option( 'mesh_activation', true );
	flush_rewrite_rules();
	do_action( 'mesh_activate' );
}

register_activation_hook( __FILE__, 'mesh_activation_hook' );

/**
 * Flush rewrite rules when the plugin is deactivated.
 */
function mesh_deactivation_hook() {
	flush_rewrite_rules();

	do_action( 'mesh_deactivate' );
}
register_deactivation_hook( __FILE__, 'mesh_deactivation_hook' );

/**
 * Get files within our directory
 *
 * @param null   $type          File Type.
 * @param int    $depth         Directory Depth.
 * @param bool   $search_parent Search through the parent directory.
 * @param string $directory     Starting Directory.
 *
 * @return array
 */
function mesh_get_files( $type = null, $depth = 0, $search_parent = false, $directory = '' ) {
	$files = (array) Mesh::scandir( $directory, $type, $depth );

	if ( $search_parent && dirname( $directory ) ) {
		$files += (array) Mesh::scandir( $directory, $type, $depth );
	}

	return $files;
}

/**
 * Load a list of templates.
 * This is heavily based on the WordPress core template loading with some slight modifications
 * to search for specific template strings.
 *
 * @return mixed
 */
function mesh_locate_template_files() {
	$current_theme = wp_get_theme();

	$section_templates = array();

	$plugin_template_files = (array) mesh_get_files( 'php', 1, false, LINCHPIN_MESH___PLUGIN_DIR . 'templates/' );

	// Loop through our local plugin templates.
	foreach ( $plugin_template_files as $plugin_file => $plugin_file_full_path ) {

		// Skip the file if it doesn't exist.
		if ( ! file_exists( $plugin_file_full_path ) ) {
			continue;
		}

		$template_contents = file_get_contents( $plugin_file_full_path );

		if ( ! preg_match( '|Mesh Template:(.*)$|mi', $template_contents, $header ) ) {
			continue;
		}

		$section_templates[ $plugin_file ]['file'] = _cleanup_header_comment( $header[1] );

		if ( preg_match( '/Mesh Template Blocks: ?([0-9]{1,2})$/mi', $template_contents, $block_header ) ) {
			$section_templates[ $plugin_file ]['blocks'] = $block_header[1];
		}
	}

	$files = (array) $current_theme->get_files( 'php', 1 );

	// Loop through our theme templates. This should be made into utility method.
	foreach ( $files as $file => $full_path ) {

		$file_contents = file_get_contents( $full_path );

		if ( ! preg_match( '|Mesh Template:(.*)$|mi', $file_contents, $header ) ) {
			continue;
		}

		$section_templates[ $file ]['file'] = _cleanup_header_comment( $header[1] );

		if ( preg_match( '/Mesh Template Blocks: ?([0-9]{1,2})$/mi', $file_contents, $block_header ) ) {
			$section_templates[ $file ]['blocks'] = (int) $block_header[1];
		}
	}

	/**
	 * Filter list of page templates for a theme.
	 *
	 * This filter does not currently allow for page templates to be added.
	 *
	 * @since 0.3.5
	 *
	 * @param array        $page_templates Array of page templates. Keys are filenames,
	 *                                     values are translated names.
	 * @param WP_Theme     $this           The theme object.
	 * @param WP_Post|null $post           The post being edited, provided for context, or null.
	 */
	$section_templates = apply_filters( 'mesh_section_templates', $section_templates );

	return $section_templates;
}

/**
 * Return admin facing markup for a section.
 *
 * @access public
 *
 * @param mixed $section Current section being manipulated.
 * @param bool  $closed  Display the section closed by default.
 * @param bool  $return  Return the value instead of echo.
 *
 * @return mixed|bool|string Prints the markup of the admin panel
 */
function mesh_add_section_admin_markup( $section, $closed = false, $return = false ) {
	if ( ! is_admin() ) {
		return false;
	}

	if ( ! is_object( $section ) ) {
		$section = get_post( $section );
	}

	if ( ! current_user_can( 'edit_post', $section->ID ) ) {
		return false;
	}

	$templates = mesh_locate_template_files();

	// Make sure we always have a template.
	$selected_template = get_post_meta( $section->ID, '_mesh_template', true );

	if ( empty( $selected_template ) ) {
		$selected_template = 'mesh-columns-1.php';
	}

	// This block count is determined by the selected template above.
	// It's important to pass this to the admin to control if a
	// section's blocks have a post_status of publish or draft.
	$block_count       = $templates[ $selected_template ]['blocks'];
	$featured_image_id = get_post_thumbnail_id( $section->ID );
	$parents           = get_post_ancestors( $section->ID );
	$section_parent_id = ( $parents ) ? $parents[ count( $parents ) - 1 ] : $section->ID;
	$section_parent    = get_post( $section_parent_id );

	if ( $return ) {
		ob_start();
	}

	include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-container.php';

	if ( $return ) {
		return ob_end_flush();
	}

	return false;
}

/**
 * Retrieve Mesh sections.
 *
 * @param int|string $post_id        Post ID.
 * @param string     $return_type    Object Return Type.
 * @param array      $statuses       Statuses to query.
 *
 * @return array|WP_Query
 */
function mesh_get_sections( $post_id = '', $return_type = 'array', $statuses = array( 'publish' ) ) {

	// If no Post ID fall back to the current global ID.
	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	$args = array(
		'post_type'      => 'mesh_section',
		'posts_per_page' => apply_filters( 'mesh_templates_per_page', 50 ),
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_parent'    => (int) $post_id,
		'post_status'    => $statuses,
	);

	if ( isset( $_GET['preview_id'] ) && isset( $_GET['preview_nonce'] ) ) { // WPCS: input var okay.
		$id = intval( $_GET['preview_id'] ); // WPCS: input var okay, sanitization ok.

		if ( false === wp_verify_nonce( sanitize_key( $_GET['preview_nonce'] ), 'post_preview_' . $id ) ) { // WPCS: input var okay.
			wp_die( esc_html__( 'Sorry, you are not allowed to preview drafts.', 'mesh' ) );
		}

		$args['post_status'] = array_merge( $args['post_status'], array( 'draft' ) );
	}

	$content_sections = new WP_Query( $args );

	switch ( $return_type ) {
		case 'query':
			return $content_sections;
		case 'array':
		default:
			return $content_sections->posts;
	}
}

/**
 * Load a specified template file for a section
 *
 * @access public
 *
 * @param string $post_id Post ID of the target Section.
 *
 * @return void
 */
function the_mesh_content( $post_id = '' ) {
	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	if ( 'mesh_section' !== get_post_type( $post_id ) ) {
		return;
	}

	$template = get_post_meta( $post_id, '_mesh_template', true );

	if ( empty( $template ) ) {
		$template = 'mesh-columns-1.php';
	}

	$located = locate_template( sanitize_text_field( $template ), true, false );

	if ( $located ) {
		return;
	} else {

		$file = LINCHPIN_MESH___PLUGIN_DIR . '/templates/' . $template;

		if ( file_exists( $file ) ) {
			include $file; // @todo evaluate for security
		} else {
			/*
			 * Add in a default template just in case one the default templates have been deleted.
			 */
			?>
			<div <?php post_class(); ?>>
				<h3 title="<?php the_title_attribute(); ?>"><?php the_title(); ?></h3>
				<div class="entry">
					<?php the_content(); ?>
				</div>
			</div>
			<?php
		}
	}
}

/**
 * Display all published "Sections"
 *
 * @access public
 *
 * @param string $post_id Target Post ID used to query child Sections.
 *
 * @return void
 */

/**
 * Display our mesh sections.
 * By default this method will echo the contents similar to
 * a traditional loop. Else it will return the contents of the
 * rendered html.
 *
 * @param string $post_id Post ID.
 * @param bool   $echo    Echo the sections or not.
 *
 * @return string
 */
function mesh_display_sections( $post_id = '', $echo = true ) {
	global $post, $mesh_section_query;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	// Do not show blocks if parent post is private.
	if ( 'private' === get_post_status( $post_id ) && ! current_user_can( 'edit_posts' ) ) {
		return '';
	}

	$mesh_section_query = mesh_get_sections( $post_id, 'query' );

	if ( empty( $mesh_section_query ) ) {
		return '';
	}

	if ( empty( $mesh_section_query ) ) {
		return '';
	}

	if ( true === $echo ) {
		do_action( 'mesh_sections_before' );

		if ( $mesh_section_query->have_posts() ) {
			while ( $mesh_section_query->have_posts() ) {
				$mesh_section_query->the_post();
				the_mesh_content();
			}
			wp_reset_postdata();
		}

		do_action( 'mesh_sections_after' );
	} else {
		ob_start();

		do_action( 'mesh_sections_before' );

		if ( $mesh_section_query->have_posts() ) {
			while ( $mesh_section_query->have_posts() ) {
				$mesh_section_query->the_post();
				the_mesh_content();
			}
			wp_reset_postdata();
		}

		do_action( 'mesh_sections_after' );

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	return '';
}

/**
 * Get a specified Section's Blocks.
 *
 * @access public
 *
 * @param  int    $section_id  Post ID of the target Section.
 * @param  string $post_status Post Status of the target Section.
 * @param  int    $number_needed The amount of blocks needed.
 *
 * @return array
 */
function mesh_get_section_blocks( $section_id, $post_status = 'publish', $number_needed = 50 ) {

	$args = array(
		'post_type'      => 'mesh_section',
		'post_status'    => $post_status,
		'posts_per_page' => $number_needed,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_parent'    => (int) $section_id,
	);

	if ( isset( $_GET['preview_id'] ) && isset( $_GET['preview_nonce'] ) ) { // Input var okay.
		$id = intval( $_GET['preview_id'] ); // WPCS: Input var okay, sanitization ok.

		if ( false === wp_verify_nonce( sanitize_key( $_GET['preview_nonce'] ), 'post_preview_' . $id ) ) { // WPCS: Input var okay, sanitization ok.
			wp_die( esc_html__( 'Sorry, you are not allowed to preview drafts.', 'mesh' ) );
		}

		// Make sure $post_status is an array.
		$args['post_status'] = is_array( $args['post_status'] ) ? $args['post_status'] : array( $args['post_status'] );
		$args['post_status'] = array_merge( $args['post_status'], array( 'draft' ) );
	}

	$content_blocks = new WP_Query( $args );

	if ( $content_blocks->have_posts() ) {
		return $content_blocks->posts;
	} else {
		return array();
	}
}

/**
 * Cleanup our mesh sections.
 *
 * Create more blocks if needed
 * If Less blocks are needed set the status of the extra blocks to "draft"
 *
 * @since 1.1
 *
 * @param object $section       Section.
 * @param int    $number_needed Amount of columns to create.
 *
 * @return array
 */
function mesh_cleanup_section_blocks( $section, $number_needed = 0 ) {

	$blocks = mesh_get_section_blocks( $section->ID, array( 'publish', 'draft' ) );
	$count  = count( $blocks );

	// Create enough blocks to fill the section.
	if ( $count < $number_needed ) {
		return mesh_maybe_create_section_blocks( $section, $number_needed );
	}

	if ( $count > $number_needed ) {

		// Make sure all child sections aren't enough blocks to fill the section.
		$start = $count - $number_needed;

		while ( $start < $count ) {

			wp_update_post( array(
				'ID'          => $blocks[ $start ]->ID,
				'post_status' => 'draft',
			) );

			++$start;
		}
	}

	return mesh_maybe_create_section_blocks( $section, $number_needed );
}

/**
 * Make sure a section has a certain number of blocks
 *
 * @uses   mesh_get_section_blocks() to get all blocks within a defined section.
 *
 * @access public
 * @param  mixed $section       Post Object for the defined Section.
 * @param  int   $number_needed Number of Blocks that need to be created.
 *
 * @return array
 */
function mesh_maybe_create_section_blocks( $section, $number_needed = 0 ) {

	if ( empty( $section ) ) {
		return array();
	}

	$blocks = mesh_get_section_blocks( $section->ID, array( 'publish', 'draft' ) );
	$count  = count( $blocks );
	$start  = $count;

	if ( $count < $number_needed ) {

		// Create enough blocks to fill the section.
		while ( $count < $number_needed ) {
			wp_insert_post( array(
				'post_type'   => 'mesh_section',
				'post_status' => $section->post_status,
				'post_title'  => esc_html__( 'No Column Title', 'mesh' ),
				'post_parent' => $section->ID,
				'menu_order'  => ( $start + $count ),
				'post_name'   => 'section-' . $section->ID . '-block-' . ( $start + $count ),
			) );

			++$count;
		}

		/*
		 * If we have more blocks than we need. Set the extras to draft and make sure the
		 * blocks that should be visible match the status of the parent section.
		 */
	} else {
		$total = $count;

		while ( $total > $number_needed ) {
			wp_update_post( array(
				'ID'          => $blocks[ $total - 1 ]->ID,
				'post_status' => 'draft',
			) );

			$total--;
		}

		// Set the rest to what we need.
		$start = 0;
		while ( $start < $number_needed ) {
			wp_update_post(array(
				'ID'          => $blocks[ $start ]->ID,
				'post_status' => $section->post_status,
			) );

			$start++;
		}
	}

	return mesh_get_section_blocks( $section->ID, array( 'publish', 'draft' ), $number_needed );
}

