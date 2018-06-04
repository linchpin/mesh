<?php
/**
 * Filesystem related global methods
 *
 * @since 1.3
 */

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
