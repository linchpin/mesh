<?php
/**
 * Plugin Name: Mesh for WordPress
 * Plugin URI: http://linchpin.agency/wordpress-plugins/mesh
 * Description: Adds multiple sections for content on a post by post basis. Mesh also has settings to enable it for specific post types
 * Version: 1.0.0
 * Author: Linchpin
 * Author URI: http://linchpin.agency
 * License: GPLv2 or later
 *
 * @package Mesh
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

define( 'LINCHPIN_MCS_VERSION', '1.0.0' );
define( 'LINCHPIN_MCS_PLUGIN_NAME', 'Multiple Content Sections' );
define( 'LINCHPIN_MCS__MINIMUM_WP_VERSION', '4.0' );
define( 'LINCHPIN_MCS___PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LINCHPIN_MCS___PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

include_once 'class.mesh-settings.php';
include_once 'class.multiple-content-sections.php';

$multiple_content_sections = new Multiple_Content_Sections();

add_action( 'init', array( 'Mesh_Settings', 'init' ) );

/**
 * Get files within our directory
 *
 * @param null       $type
 * @param int        $depth
 * @param bool|false $search_parent
 *
 * @return array
 */
function mcs_get_files( $type = null, $depth = 0, $search_parent = false, $directory = '' ) {
	$files = (array) Multiple_Content_Sections::scandir( $directory, $type, $depth );

	if ( $search_parent && $this->parent() ) {
	    $files += (array) Multiple_Content_Sections::scandir( $directory, $type, $depth );
	}

	return $files;
}

/**
 * Load a list of templates.
 *
 * @param string $section_templates (default: '') Our list of available templates.
 *
 * @return mixed
 */
function mcs_locate_template_files( $section_templates = '' ) {
	$current_theme = wp_get_theme();

	$section_templates = array();

	$plugin_template_files = (array) mcs_get_files( 'php', 1, false, LINCHPIN_MCS___PLUGIN_DIR . 'templates/' );

	// Loop through our local plugin templates.
	foreach( $plugin_template_files as $plugin_file => $plugin_file_full_path ) {
		if ( ! preg_match( '|MCS Template:(.*)$|mi', file_get_contents( $plugin_file_full_path ), $header ) ) {
			continue;
		}

		$section_templates[ $plugin_file ]['file'] = _cleanup_header_comment( $header[1] );

		if ( preg_match( '/MCS Template Blocks: ?([0-9]{1,2})$/mi', file_get_contents( $plugin_file_full_path ), $block_header ) ) {
			$section_templates[ $plugin_file ]['blocks'] = $block_header[1];
		}
	}

	$files = (array) $current_theme->get_files( 'php', 1 );

	// Loop through our theme templates. This should be made into utility method.
	foreach ( $files as $file => $full_path ) {

		if ( ! preg_match( '|MCS Template:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
			continue;
		}

		$section_templates[ $file ]['file'] = _cleanup_header_comment( $header[1] );

		if ( preg_match( '/MCS Template Blocks: ?([0-9]{1,2})$/mi', file_get_contents( $full_path ), $block_header ) ) {
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
	$return = apply_filters( 'mcs_section_templates', $section_templates );

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
function mcs_add_section_admin_markup( $section, $closed = false ) {
	if ( ! is_admin() ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $section->ID ) ) {
		return;
	}

	$templates = mcs_locate_template_files();

	// Make sure we always have a template.
	if ( ! $selected_template = get_post_meta( $section->ID, '_mcs_template', true ) ) {
		$selected_template = 'mcs-columns-1.php';
	}

	$css_class     = get_post_meta( $section->ID, '_mcs_css_class', true );
	$lp_equal      = get_post_meta( $section->ID, '_mcs_lp_equal', true );
	$offset        = get_post_meta( $section->ID, '_mcs_offset', true );
	$title_display = get_post_meta( $section->ID, '_mcs_title_display', true );
	$push_pull     = get_post_meta( $section->ID, '_mcs_push_pull', true );
	$collapse      = get_post_meta( $section->ID, '_mcs_collapse', true ); // Collapse our column spacing.

	$featured_image_id = get_post_thumbnail_id( $section->ID );

	include LINCHPIN_MCS___PLUGIN_DIR . 'admin/section-container.php';
}

/**
 * @param $post_id
 * @param string $return_type
 *
 * @return array|WP_Query
 */
function mcs_get_sections( $post_id, $return_type = 'array' ) {
	$content_sections = new WP_Query( array(
		'post_type' => 'mcs_section',
		'posts_per_page' => 50,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_parent' => (int) $post_id,
	) );

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
function the_mcs_content( $post_id = '' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	if ( 'mcs_section' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! $template = get_post_meta( $post_id, '_mcs_template', true ) ) {
		$template = 'mcs-columns-1.php';
	}

	$located = locate_template( sanitize_text_field( $template ), true, false );

	if ( $located ) {
		return;
	} else {

		$file = LINCHPIN_MCS___PLUGIN_DIR . '/templates/' . $template;

		if ( file_exists( $file ) ) {
			include $file;
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
function mcs_display_sections( $post_id = '' ) {
	global $post, $mcs_section_query;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	// Do not show blocks if parent post is private.
	if ( 'private' === get_post_status( $post_id ) && ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	if ( ! $mcs_section_query = mcs_get_sections( $post_id, 'query' ) ) {
		return;
	}

	if ( ! empty( $mcs_section_query ) ) {
		if ( $mcs_section_query->have_posts() ) {
			while ( $mcs_section_query->have_posts() ) {
				$mcs_section_query->the_post();
				the_mcs_content();
			}
		}
		wp_reset_postdata();
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
function mcs_get_section_blocks( $section_id, $post_status = 'publish' ) {
	$content_blocks = new WP_Query( array(
		'post_type' => 'mcs_section',
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
 * @todo: Should always be at least 1 section?
 *
 * @access public
 *
 * @param  mixed $section       Post Object for the defined Section
 * @param  int   $number_needed Number of Blocks that need to be created.
 *
 * @return array
 */
function mcs_maybe_create_section_blocks( $section, $number_needed = 0 ) {

	$blocks = mcs_get_section_blocks( $section->ID, $section->post_status );
	$count = count( $blocks );
	$start = $count;

	// Create enough blocks to fill the section.
	while ( $count < $number_needed ) {
		wp_insert_post( array(
			'post_type'   => 'mcs_section',
			'post_status' => $section->post_status,
			'post_title'  => 'Block ' . ( $start + $count ),
			'post_parent' => $section->ID,
			'menu_order'  => ( $start + $count ),
			'post_name'   => 'section-' . $section->ID . '-block-' . ( $start + $count ),
		) );

		++$count;
	}

	return mcs_get_section_blocks( $section->ID, $section->post_status );
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
function mcs_section_background( $post_id = 0, $echo = true, $size_large = 'large', $size_medium = 'large' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
	}

	if ( has_post_thumbnail() ) {

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
}

/**
 * Return an array of allowed html for wp_kses functions
 *
 * @return mixed|void
 */
function mcs_get_allowed_html() {
	$mcs_allowed = apply_filters( 'mcs_default_allowed_html', array(
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

	return apply_filters( 'mcs_allowed_html', array_merge_recursive( $post_allowed, $mcs_allowed ) );
}