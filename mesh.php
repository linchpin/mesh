<?php
/**
 * Plugin Name: Mesh
 * Plugin URI: https://meshplugin.com?utm_source=mesh&utm_medium=plugin-admin-page&utm_campaign=wp-plugin
 * Description: Adds multiple sections for content on a post by post basis. Mesh also has settings to enable it for specific post types
 * Version: 1.2.4
 * Text Domain: mesh
 * Domain Path: /languages
 * Author: Linchpin
 * Author URI: https://linchpin.agency/?utm_source=mesh&utm_medium=plugin-admin-page&utm_campaign=wp-plugin
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
define( 'LINCHPIN_MESH_VERSION', '1.2.4' );
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

include_once 'class.mesh-settings.php';
include_once 'class.mesh-controls.php';
include_once 'class.mesh-templates.php';
include_once 'class.mesh-pointers.php';
include_once 'class.mesh.php';
include_once 'class.mesh-upgrades.php';
include_once 'class.mesh-reponsive-grid.php';
include_once 'class.mesh-integrations.php';

if ( is_admin() ) {
	include_once 'class.mesh-install.php';
}

$mesh          = new Mesh();

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
 * Return the classes used to build our grid structure
 * Return a string of classes back to our block.
 *
 * @since 1.1
 *
 * @param int   $block_id Block ID.
 * @param array $args     Passed arguments.
 */
function mesh_block_class( $block_id, $args = array() ) {

	$defaults = array(
		'push_pull'        => false,
		'collapse_spacing' => false,
		'total_columns'    => 1,
		'max_columns'      => apply_filters( 'mesh_max_columns', 12 ),
		'column_index'     => -1,
		'column_width'     => 12,
	);

	$args = wp_parse_args( $args, $defaults );

	$grid_system = apply_filters( 'mesh_grid_system', 'foundation' );

	$grid = Mesh_Responsive_Grid::get_responsive_grid( $grid_system );

	$block_css_class = get_post_meta( $block_id, '_mesh_css_class', true );
	$block_offset    = (int) get_post_meta( $block_id, '_mesh_offset', true );

	$classes = array(
		$grid['columns_class'],
		$grid['columns']['small'] . '-' . $args['max_columns'],
	);

	$classes[] = $grid['columns']['medium'] . '-' . ( (int) $args['column_width'] - $block_offset );

	if ( $block_offset ) {
		$classes[] = $grid['columns']['medium'] . '-' . $grid['offset'] . '-' . $block_offset;
	}

	if ( ! empty( $args['push_pull'] ) ) {

		$push_or_pull = '';

		if ( 2 === (int) $args['total_columns'] ) {

			switch ( (int) $args['column_index'] ) {
				case 0:
					$push_or_pull = 'push';
					break;
				case 1:
					$push_or_pull = 'pull';
					break;
			}

			if ( ! empty( $push_or_pull ) ) {
				$classes[] = $grid['columns']['medium'] . '-' . $push_or_pull . '-' . ( $args['max_columns'] - $args['column_width'] );
			}
		}
	}

	// Merge our block classes (from the input field).
	if ( ! empty( $block_css_class ) ) {
		$block_css_class = explode( ' ', $block_css_class );
		$classes = array_merge( $classes, $block_css_class );
	}

	$classes = array_map( 'sanitize_html_class', $classes );
	$classes = array_unique( $classes );

	echo 'class="' . join( ' ', $classes ) . '"'; // WPCS: XSS ok, sanitization ok.
}

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

	if ( $search_parent && $this->parent() ) {
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
	$css_class         = get_post_meta( $section->ID, '_mesh_css_class', true );
	$lp_equal          = get_post_meta( $section->ID, '_mesh_lp_equal', true );
	$offset            = get_post_meta( $section->ID, '_mesh_offset', true );
	$title_display     = get_post_meta( $section->ID, '_mesh_title_display', true );
	$push_pull         = get_post_meta( $section->ID, '_mesh_push_pull', true );
	$collapse          = get_post_meta( $section->ID, '_mesh_collapse', true ); // Collapse our column spacing.
	$featured_image_id = get_post_thumbnail_id( $section->ID );

	$parents = get_post_ancestors( $section->ID );
	$section_parent_id = ($parents) ? $parents[ count( $parents ) - 1 ] : $section->ID;
	$section_parent = get_post( $section_parent_id );

	if ( $return ) {
		ob_start();
	}
	include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-container.php';
	if ( $return ) {
		return ob_end_flush();
	}
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
		'post_type' => 'mesh_section',
		'post_status' => $post_status,
		'posts_per_page' => $number_needed,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_parent' => (int) $section_id,
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
	$count = count( $blocks );

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
	$count = count( $blocks );
	$start = $count;

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
				'ID' => $blocks[ $total - 1 ]->ID,
				'post_status' => 'draft',
			) );

			$total--;
		}

		// Set the rest to what we need.
		$start = 0;
		while ( $start < $number_needed ) {
			wp_update_post(array(
				'ID' => $blocks[ $start ]->ID,
				'post_status' => $section->post_status,
			) );

			$start++;
		}
	}

	return mesh_get_section_blocks( $section->ID, array( 'publish', 'draft' ), $number_needed );
}

/**
 * Utility Method to add a background to a section
 *
 * @todo This should be disabled if the user selects to NOT use foundation.
 * @todo There is definitely a need for some optimization here. Lots of code duplication that could use
 *       a utility method or two.
 *
 * @param int    $post_id     PostID of the Section.
 * @param bool   $echo        Echo the output or not.
 * @param string $size_large  The name of the Thumbnail for our Large image used by Interchange.
 * @param string $size_medium The name of the Thumbnail for our Medium image used by Interchange.
 * @param string $size_xlarge The name of the Thumbnail for our XLarge image used by Interchange.
 * @param string $size_small  The name of the Thumbnail for our small image used by Interchange.
 *
 * @return array|string
 */
function mesh_section_background( $post_id = 0, $echo = true, $size_large = 'large', $size_medium = 'large', $size_xlarge = 'large', $size_small = 'small' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
	}

	if ( has_post_thumbnail( $post_id ) ) {

		$backgrounds = array();

		$mesh_options       = get_option( 'mesh_settings', array(
			'foundation_version' => 5,
		) );

		$foundation_version = (int) $mesh_options['foundation_version'];
		$css_mode           = $mesh_options['css_mode'];

		$default_bg_size = apply_filters( 'mesh_default_bg_size', 'mesh-background' );
		$size_medium     = apply_filters( 'mesh_small_bg_size', $size_small );
		$size_medium     = apply_filters( 'mesh_medium_bg_size', $size_medium );
		$size_large      = apply_filters( 'mesh_large_bg_size', $size_large );
		$size_xlarge     = apply_filters( 'mesh_xlarge_bg_size', $size_xlarge );

		$default_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $default_bg_size );

		$using_foundation = ( 0 === (int) $css_mode || 1 === (int) $css_mode );

		// Only allow interchange or backgrounds when using Mesh css or a theme based on foundation.
		if ( $using_foundation ) {

			if ( 0 === (int) $css_mode ) {
				$foundation_version = 6;
			}

			switch ( $foundation_version ) {
				case 6:
					$interchange_format = '[%s, %s]';
					break;
				default:
					$interchange_format = '[%s, (%s)]';
			}

			$background_urls = array();

			if ( ! empty( $default_image_url ) ) {
				if ( ! empty( $default_image_url[0] ) && '' !== $default_image_url[0] ) {

					// Foundation 6 doesn't use default.
					if ( 6 !== $foundation_version ) {
						$background_urls[] = $default_image_url[0];
						$backgrounds[] = sprintf( $interchange_format, $default_image_url[0], 'default' );
					}

					$small_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size_medium );

					if ( ! empty( $small_image_url ) ) {
						if ( ! empty( $small_image_url[0] ) && '' !== $small_image_url[0] ) {
							if ( ! in_array( $small_image_url[0], $background_urls, true ) ) {
								$background_urls[] = $small_image_url[0];
								$backgrounds[] = sprintf( $interchange_format, $small_image_url[0], 'small' );
							}
						}
					}

					$medium_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size_medium );

					if ( ! empty( $medium_image_url ) ) {
						if ( ! empty( $medium_image_url[0] ) && '' !== $medium_image_url[0] ) {
							if ( ! in_array( $medium_image_url[0], $background_urls, true ) ) {
								$background_urls[] = $medium_image_url[0];
								$backgrounds[]     = sprintf( $interchange_format, $medium_image_url[0], 'medium' );
							}
						}
					}

					$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size_large );

					if ( ! empty( $large_image_url ) ) {
						if ( ! empty( $large_image_url[0] ) && '' !== $large_image_url[0] ) {
							if ( ! in_array( $large_image_url[0], $background_urls, true ) ) {
								$background_urls[] = $large_image_url[0];
								$backgrounds[] = sprintf( $interchange_format, $large_image_url[0], 'large' );
							}
						}
					}

					$xlarge_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size_xlarge );

					if ( ! empty( $xlarge_image_url ) ) {
						if ( ! empty( $xlarge_image_url[0] ) && '' !== $xlarge_image_url[0] ) {
							if ( ! in_array( $xlarge_image_url[0], $background_urls, true ) ) {
								$background_urls[] = $xlarge_image_url[0];
								$backgrounds[] = sprintf( $interchange_format, $xlarge_image_url[0], 'xlarge' );
							}
						}
					}
				}

				if ( empty( $backgrounds ) ) {
					return array();
				}
			}
		}

		$style = '';

		if ( is_array( $backgrounds ) && ! empty( $backgrounds ) && $using_foundation ) {
			$style .= 'data-interchange="' . esc_attr( implode( ', ', $backgrounds ) ) . '"';
		}

		if ( '' !== $default_image_url[0] ) {
			$style .= ' style="background-image: url(' . esc_url( $default_image_url[0] ) . ');"';
		}
	}

	if ( empty( $style ) ) {
		return '';
	} else {
		if ( false === $echo ) {
			return $style;
		} else {
			echo $style; // WPCS: XSS ok.
		}
	}

	return $style;
}

/**
 * Return an array of allowed html for wp_kses functions
 *
 * @return mixed
 */
function mesh_get_allowed_html() {
	$mesh_allowed = apply_filters( 'mesh_default_allowed_html', array(
		'iframe' => array(
			'src' => true,
			'style' => true,
			'id' => true,
			'class' => true,
			'name' => true,
			'allowfullscreen' => true,
			'msallowfullscreen' => true,
			'mozallowfullscreen' => true,
			'webkitallowfullscreen' => true,
			'oallowfullscreen' => true,
			'allowtransparency' => true,
			'frameborder' => true,
			'scrolling' => true,
			'width' => true,
			'height' => true,
		),
		'script' => array(
			'src' => true,
		),
		'div' => array(
			'data-equalizer' => true,
			'data-equalizer-watch' => true,
			'data-interchange' => true,
			'data-lp-equal' => true,
			'data-lp-equal-items' => true,
			'data-lp-equal-children' => true,
		),
		'section' => array(
			'data-interchange' => true,
		),
		'span' => array(
			'class' => true,
			'style' => true,
			'id'    => true,
			'data-equalizer' => true,
			'data-equalizer-watch' => true,
			'data-interchange' => true,
			'data-lp-equal' => true,
			'data-lp-equal-items' => true,
			'data-lp-equal-children' => true,
		),
		'input' => array(
			'align' => true,
			'type' => true,
			'name' => true,
			'class' => true,
			'id' => true,
			'list' => true,
			'value' => true,
			'required' => true,
			'placeholder' => true,
			'checked' => true,
			'disabled' => true,
			'max' => true,
			'min' => true,
			'maxlength' => true,
			'size' => true,
			'hidden' => true,
			'aria-required' => true,
			'aria-labelledby' => true,
			'aria-invalid' => true,
			'aria-checked' => true,
		),
		'option' => array(
			'value' => true,
		),
		'textarea' => array(
			'maxlength' => true,
			'placeholder' => true,
			'required' => true,
			'aria-required' => true,
			'aria-labelledby' => true,
			'aria-invalid' => true,
		),
		'select' => array(
			'name' => true,
			'disabled' => true,
			'multiple' => true,
			'required' => true,
			'size' => true,
			'aria-required' => true,
			'aria-labelledby' => true,
			'aria-invalid' => true,
		),
		'fieldset' => array(
			'name' => true,
			'disabled' => true,
		),
	) );

	$post_allowed = wp_kses_allowed_html( 'post' );

	return apply_filters( 'mesh_allowed_html', array_merge_recursive( $post_allowed, $mesh_allowed ) );
}
