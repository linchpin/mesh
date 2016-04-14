<?php
/*
 * Plugin Name: Mesh
 * Plugin URI: http://linchpin.agency/wordpress-plugins/mesh
 * Description: Adds multiple sections for content on a post by post basis. Mesh also has settings to enable it for specific post types
 * Version: 1.0.0
 * Text Domain: linchpin-mesh
 * Domain Path: /languages
 * Author: Linchpin Agency (Aaron Ware, Max Morgan & Jonathan Desrosiers)
 * Author URI: http://linchpin.agency/?utm_source=simple-subtitles-for-wordpress&utm_medium=plugin-admin-page&utm_campaign=wp-plugin
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
define( 'LINCHPIN_MESH_VERSION', '1.0.0' );
define( 'LINCHPIN_MESH_PLUGIN_NAME', 'Mesh' );
define( 'LINCHPIN_MESH__MINIMUM_WP_VERSION', '4.0' );
define( 'LINCHPIN_MESH___PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LINCHPIN_MESH___PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

include_once 'class.mesh-settings.php';
include_once 'class.mesh-pointers.php';
include_once 'class.mesh.php';

$mesh          = new Mesh();
$mesh_pointers = new Mesh_Admin_Pointers();

add_action( 'init', array( 'Mesh_Settings', 'init' ) );

/**
 * Flush rewrite rules when the plugin is activated.
 */
function mesh_activation_hook() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mesh_activation_hook' );

/**
 * Flush rewrite rules when the plugin is deactivated.
 */
function mesh_deactivation_hook() {
	flush_rewrite_rules();
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
		if ( ! preg_match( '|Mesh Template:(.*)$|mi', file_get_contents( $plugin_file_full_path ), $header ) ) {
			continue;
		}

		$section_templates[ $plugin_file ]['file'] = _cleanup_header_comment( $header[1] );

		if ( preg_match( '/Mesh Template Blocks: ?([0-9]{1,2})$/mi', file_get_contents( $plugin_file_full_path ), $block_header ) ) {
			$section_templates[ $plugin_file ]['blocks'] = $block_header[1];
		}
	}

	$files = (array) $current_theme->get_files( 'php', 1 );

	// Loop through our theme templates. This should be made into utility method.
	foreach ( $files as $file => $full_path ) {

		if ( ! preg_match( '|Mesh Template:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
			continue;
		}

		$section_templates[ $file ]['file'] = _cleanup_header_comment( $header[1] );

		if ( preg_match( '/Mesh Template Blocks: ?([0-9]{1,2})$/mi', file_get_contents( $full_path ), $block_header ) ) {
			$section_templates[ $file ]['blocks'] = (int) $block_header[0];
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
 * @param  object $section Current section being manipulated.
 * @param  bool|false $closed
 * @return void Prints the markup of the admin panel
 */
function mesh_add_section_admin_markup( $section, $closed = false ) {
	if ( ! is_admin() ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $section->ID ) ) {
		return;
	}

	$templates = mesh_locate_template_files();

	// Make sure we always have a template.
	if ( ! $selected_template = get_post_meta( $section->ID, '_mesh_template', true ) ) {
		$selected_template = 'mesh-columns-1.php';
	}

	$css_class         = get_post_meta( $section->ID, '_mesh_css_class', true );
	$lp_equal          = get_post_meta( $section->ID, '_mesh_lp_equal', true );
	$offset            = get_post_meta( $section->ID, '_mesh_offset', true );
	$title_display     = get_post_meta( $section->ID, '_mesh_title_display', true );
	$push_pull         = get_post_meta( $section->ID, '_mesh_push_pull', true );
	$collapse          = get_post_meta( $section->ID, '_mesh_collapse', true ); // Collapse our column spacing.
	$featured_image_id = get_post_thumbnail_id( $section->ID );

	include LINCHPIN_MESH___PLUGIN_DIR . 'admin/section-container.php';
}

/**
 * Retrieve Mesh sections.
 *
 * @param int    $post_id        Post ID.
 * @param string $return_type    Object Return Type.
 * @param array  $statuses       Statuses to query.
 *
 * @return array|WP_Query
 */
function mesh_get_sections( $post_id, $return_type = 'array', $statuses = array( 'publish' ) ) {
	$args = array(
		'post_type' => 'mesh_section',
		'posts_per_page' => 50,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_parent' => (int) $post_id,
		'post_status' => $statuses,
	);

	$content_sections = new WP_Query( $args );

	error_log( print_r( $content_sections, true ) );

	switch ( $return_type ) {
		case 'query' :
			return $content_sections;
			break;

		case 'array' :
		default      :
			return $content_sections->posts;
			break;
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

	if ( ! $template = get_post_meta( $post_id, '_mesh_template', true ) ) {
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
function mesh_display_sections( $post_id = '' ) {
	global $post, $mesh_section_query;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	// Do not show blocks if parent post is private.
	if ( 'private' === get_post_status( $post_id ) && ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	if ( ! $mesh_section_query = mesh_get_sections( $post_id, 'query' ) ) {
		return;
	}

	if ( ! empty( $mesh_section_query ) ) {
		if ( $mesh_section_query->have_posts() ) {
			while ( $mesh_section_query->have_posts() ) {
				$mesh_section_query->the_post();
				the_mesh_content();
			}
			wp_reset_postdata();
		}
	}
}

/**
 * Get a specified Section's Blocks.
 *
 * @access public
 *
 * @param  int    $section_id  Post ID of the target Section.
 * @param  string $post_status Post Status of the target Section.
 *
 * @return array
 */
function mesh_get_section_blocks( $section_id, $post_status = 'publish' ) {
	$content_blocks = new WP_Query( array(
		'post_type' => 'mesh_section',
		'post_status' => $post_status,
		'posts_per_page' => 50,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_parent' => (int) $section_id,
	) );

	if ( $content_blocks->have_posts() ) {
		return $content_blocks->posts;
	} else {
		return array();
	}
}

/**
 * Make sure a section has a certain number of blocks
 *
 * @todo Should always be at least 1 section?
 *
 * @access public
 * @param  mixed $section       Post Object for the defined Section.
 * @param  int   $number_needed Number of Blocks that need to be created.
 *
 * @return array
 */
function mesh_maybe_create_section_blocks( $section, $number_needed = 0 ) {

	$blocks = mesh_get_section_blocks( $section->ID, $section->post_status );
	$count = count( $blocks );
	$start = $count;

	// Create enough blocks to fill the section.
	while ( $count < $number_needed ) {
		wp_insert_post( array(
			'post_type'   => 'mesh_section',
			'post_status' => $section->post_status,
			'post_title'  => __( 'No Column Title', 'linchpin-mesh' ),
			'post_parent' => $section->ID,
			'menu_order'  => ( $start + $count ),
			'post_name'   => 'section-' . $section->ID . '-block-' . ( $start + $count ),
		) );

		++$count;
	}

	return mesh_get_section_blocks( $section->ID, $section->post_status );
}

/**
 * Utility Method to add a background to a section
 *
 * @todo This should be disabled if the user selects to NOT use foundation.
 *
 * @param int    $post_id     PostID of the Section.
 * @param bool   $echo        Echo the output or not.
 * @param string $size_large  The name of the Thumbnail for our Large image used by Interchange.
 * @param string $size_medium The name of the Thumbnail for our Medium image used by Interchange.
 *
 * @return array|string|void
 */
function mesh_section_background( $post_id = 0, $echo = true, $size_large = 'large', $size_medium = 'large' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
	}

	if ( has_post_thumbnail( $post_id ) ) {

		$backgrounds = array();

		if ( $default_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' ) ) {
			if ( ! empty( $default_image_url[0] ) && '' !== $default_image_url[0] ) {
				$backgrounds[] = '[' . $default_image_url[0] . ', (default)]';

				if ( $medium_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size_medium ) ) {
					if ( ! empty( $medium_image_url[0] ) && '' !== $medium_image_url[0] ) {
						$backgrounds[] = '[' . $medium_image_url[0] . ', (medium)]';
					}
				}

				if ( $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size_large ) ) {
					if ( ! empty( $large_image_url[0] ) && '' !== $large_image_url[0] ) {
						$backgrounds[] = '[' . $large_image_url[0] . ', (large)]';
					}
				}
			}
		}

		if ( empty( $backgrounds ) ) {
			return array();
		}

		if ( '' !== $default_image_url[0] ) {
			$style = 'data-interchange="' . implode( ', ', esc_attr( $backgrounds ) ) . '" style="background-image: url(' . esc_url( $default_image_url[0] ) . ');"';
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
 * @return mixed|void
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
	) );

	$post_allowed = wp_kses_allowed_html( 'post' );

	return apply_filters( 'mesh_allowed_html', array_merge_recursive( $post_allowed, $mesh_allowed ) );
}
