<?php
/**
 * This file includes useful utility / middle man methods used within Mesh
 * or by developers looking for more advanced implementations of Mesh
 *
 * @since 1.2.5
 */

/**
 * Build out extra element attributes.
 *
 * This is deprecated use mesh_get_element_attributes instead.
 *
 * @deprecated 1.2.5
 * @since 1.2.3
 *
 * @param mixed    $post_id Current Post ID.
 * @param string $return_type
 *
 * @return string|array
 */
function get_mesh_element_attributes( $post_id = '', $return_type = 'string' ) {
	return mesh_get_element_attributes( $post_id, $return_type );
}

/**
 * Build out extra element attributes as needed.
 *
 * @since 1.2.5
 *
 * @param mixed  $post_id Post ID.
 * @param string $return_type
 *
 * @return array|mixed|string
 */
function mesh_get_element_attributes( $post_id = '', $return_type = 'string' ) {
	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$element_attributes = array();

	$element_attributes = apply_filters( 'mesh_element_attributes', $element_attributes );

	if ( 'string' === $return_type ) {
		return implode( ' ', $element_attributes );
	}

	return $element_attributes;
}

/**
 * Build out the attributes passed to each section
 *
 * @param mixed $post_id Current Post ID.
 * @param bool  $echo    Echo the post or not.
 * @since 1.2
 *
 * @return array|string
 */
function mesh_default_section_attributes( $post_id = '', $echo = true ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	/*
	 * Process Section Meta
	 */
	$default_section_meta = array(
		'_mesh_css_class',
		'_mesh_lp_equal',
		'_mesh_title_display',
		'_mesh_push_pull',
		'_mesh_collapse',
		'_mesh_blocks',
		'post_title',
		'post_status',
		'_mesh_template',
		'template_original',
		'menu_order',
	);

	/**
	 * This filter is used to remove or add elements to the default section meta
	 *
	 * @todo "meta" related to a section
	 */
	$default_section_meta = apply_filters( 'mesh_default_section_meta_fields', $default_section_meta );

	$section_data = get_post_meta( $post_id, '' );

	$attributes = array();

	// Process our custom meta.
	foreach ( $section_data as $data_key => $data_field ) {
		// Do not process default keys.
		if ( in_array( $data_key, $default_section_meta, true ) ) {
			continue;
		}

		$lowercase_data_key = str_replace( '_mesh_', '', $data_key );

		if ( ! empty( $data_field ) ) {
			$attributes[ 'data-' . $lowercase_data_key ] = $data_field[0];
		}
	}

	if ( empty( $attributes ) ) {
		return '';
	} else {
		if ( false === $echo ) {
			return $attributes;
		} else {

			$attributes = join( ' ', array_map( function( $data_key ) use ( $attributes ) {
				if ( is_bool( $attributes[ $data_key ] ) ) {
					return $attributes[ $data_key ] ? $data_key : '';
				}
				return $data_key . '="' . $attributes[ $data_key ] . '"';
			}, array_keys( $attributes ) ) );

			echo $attributes; // WPCS: XSS ok.
		}
	}

	return $attributes;
}

/**
 * Middle man method to return the attributes used for sections.
 *
 * @since 1.2.5
 *
 * @param int    $post_id Post ID.
 * @param string $return_type
 *
 * @return array|mixed|string
 */
function mesh_get_section_attributes( $post_id = '', $return_type = 'string' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$section_attributes = mesh_get_element_attributes( $post_id, 'array' );

	$post_parent_id   = wp_get_post_parent_id( $post_id );
	$parent_post_type = get_post_type( $post_parent_id );

	if ( 'mesh_section' !== $parent_post_type ) {
		$section_id = get_post_meta( $post_id, '_mesh_section_id', true );
		$section_id = ( ! empty( $section_id ) ) ? $section_id : 'mesh-section-' . $post_id;

		$section_attributes['id'] = esc_attr( $section_id );
	}

	$attributes_html = '';

	if ( 'string' === $return_type ) {

		foreach ( $section_attributes as $key => $attribute ) {
			$attributes_html .= sprintf( '%s="%s"',
				sanitize_title( $key ),
				esc_attr( $attribute )
			);

			$attributes_html .= ' ';
		}

		return $attributes_html;
	}

	$section_attributes = apply_filters( 'mesh_section_attributes', $section_attributes );

	return $section_attributes;
}

/**
 * @since 1.2.5
 *
 * @param string $post_id
 * @param string $return_type
 */
function mesh_section_attributes( $post_id = '', $return_type = 'string' ) {
	$section_attributes = mesh_get_section_attributes( $post_id, $return_type );

	echo $section_attributes; // XSS OK. Esc done in mesh_get_section_attributes()
}

/**
 * Middle man method to return the attributes used for columns.
 *
 * @since 1.2.5
 *
 * @param mixed  $post_id Post ID.
 * @param string $return_type
 *
 * @return array|mixed|string
 */
function mesh_get_column_attributes( $post_id = '', $return_type = 'string' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$column_attributes = mesh_get_element_attributes( $post_id, 'array' );
	$grid              = mesh_get_responsive_grid();

	if ( ! isset( $grid['name'] ) || 'XY Grid' !== $grid['name'] ) {
		$lp_equal = get_post_meta( get_the_ID(), '_mesh_lp_equal', true );
		if ( ! empty( $lp_equal ) ) {
			$column_attributes['data-equalizer-watch'] = '';
		}
	}

	$column_attributes = apply_filters( 'mesh_column_attributes', $column_attributes );

	if ( 'string' === $return_type ) {

		$attributes_html = '';

		foreach ( $column_attributes as $key => $attribute ) {

			$format = '%s="%s" ';

			if ( empty( $attribute ) ) {
				$format = '%s ';
			}

			$attributes_html .= sprintf( $format,
				sanitize_title( $key ),
				esc_attr( $attribute )
			);

		}

		return trim( $attributes_html );
	}

	return $column_attributes;
}

/**
 * @since 1.2.5
 *
 * @param string $post_id
 * @param string $return_type
 */
function mesh_column_attributes( $post_id = '', $return_type = 'string' ) {
	$column_attributes = mesh_get_column_attributes( $post_id, $return_type );

	echo esc_html( $column_attributes );
}

/**
 * Middle man method to return the attributes used for rows.
 *
 * @since 1.2.5
 *
 * @param mixed  $post_id Post ID.
 * @param string $return_type
 *
 * @return array|mixed|string
 */
function mesh_get_row_attributes( $post_id = '', $return_type = 'string' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$row_attributes = mesh_get_element_attributes( $post_id, 'array' );

	$lp_equal = get_post_meta( get_the_ID(), '_mesh_lp_equal', true );

	if ( ! empty( $lp_equal ) ) {
		$row_attributes['data-equalizer']   = '';
		$row_attributes['data-equalize-on'] = 'medium';
	}

	$row_attributes = apply_filters( 'mesh_row_attributes', $row_attributes );

	if ( 'string' === $return_type ) {

		$attributes_html = '';

		foreach ( $row_attributes as $key => $attribute ) {

			$format = '%s="%s" ';

			if ( empty( $attribute ) ) {
				$format = '%s ';
			}

			$attributes_html .= sprintf( $format,
				sanitize_title( $key ),
				esc_attr( $attribute )
			);

		}

		return trim( $attributes_html );
	}

	return $row_attributes;
}

/**
 * Echo our mesh row attributes
 *
 * @since 1.2.5
 *
 * @param mixed  $post_id
 * @param string $return_type
 */
function mesh_row_attributes( $post_id = '', $return_type = 'string' ) {
	$row_attributes = mesh_get_row_attributes( $post_id, $return_type );

	echo $row_attributes; // WPCS ok, XSS ok.
}

/**
 * Get the css classes associated with our row.
 *
 * @since 1.2.5
 *
 * @param mixed $class CSS Classes passed as " " delimited string or array
 * @param mixed $post_id
 *
 * @return string
 */
function mesh_get_row_class( $class = '', $post_id = '' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$row_class = array();

	if ( $class ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$row_class = array_map( 'esc_attr', $class );
	}

	$row_class[] = 'mesh-row'; // Add in our own mesh-row class by default, (used by mesh default equalizer)

	$grid_system = mesh_get_responsive_grid();

	$custom_row_class        = explode( ' ', get_post_meta( $post_id, '_mesh_row_class', true ) );
	$collapse_column_spacing = get_post_meta( $post_id, '_mesh_collapse', true );

	$row_class[] = $grid_system['row_class'];

	if ( ! empty( $collapse_column_spacing ) ) {
		$row_class[] = 'collapse';
	}

	if ( ! empty( $custom_row_class ) ) {
		$row_class = array_merge( $row_class, $custom_row_class );
	}

	$row_class = apply_filters( 'mesh_row_classes', $row_class, $post_id );
	$row_class = array_map( 'sanitize_html_class', $row_class ); // Sanitize each class we were passed.
	$row_class = array_unique( $row_class );

	return trim( implode( ' ', $row_class ) );
}

/**
 * Output the css class
 *
 * @since 1.2.5
 *
 * @param mixed $class
 * @param mixed $post_id
 */
function mesh_row_class( $class = '', $post_id = '' ) {

	$row_class = mesh_get_row_class( $class, $post_id );

	printf( 'class="%s"', $row_class ); // WPCS: XSS ok, Sanitization ok.
}

/**
 * Get the title classes
 *
 * @since 1.2.5
 *
 * @param mixed $post_id
 *
 * @return string
 */
function mesh_get_title_class( $post_id = '' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$grid_system   = mesh_get_responsive_grid();
	$title_display = get_post_meta( $post_id, '_mesh_title_display', true );

	$title_class = array(
		'small-12', // @todo this should be filterable or part of the grid system
		$grid_system['columns_class'],
		'title-row',
	);

	if ( ! empty( $title_display ) ) {
		$title_class[] = $title_display;
	}

	$title_class = apply_filters( 'mesh_section_title_css_classes', $title_class );

	$title_class = array_map( 'sanitize_html_class', $title_class ); // Sanitize each class we were passed.

	return implode( ' ', $title_class );
}

/**
 * Return the classes used to build our grid structure
 * Return a string of classes back to our block.
 *
 * @since 1.1
 *
 * @param mixed $block_id Block ID.
 * @param array $args     Passed arguments.
 */
function mesh_block_class( $block_id, $args = array() ) {

	$defaults = array(
		'push_pull'        => false,
		'collapse_spacing' => false,
		'total_columns'    => 1,
		'max_columns'      => apply_filters( 'mesh_max_columns', 12 ),
		'column_index'     => -1,
		'column_width'     => apply_filters( 'mesh_column_width', 12 ),
	);

	$args            = wp_parse_args( $args, $defaults );
	$grid            = mesh_get_responsive_grid();
	$column_width    = (int) get_post_meta( $block_id, '_mesh_column_width', true );
	$block_css_class = get_post_meta( $block_id, '_mesh_css_class', true );
	$block_centered  = get_post_meta( $block_id, '_mesh_centered', true );
	$block_offset    = (int) get_post_meta( $block_id, '_mesh_offset', true );

	// Define our column width
	if ( ! empty( $column_width ) ) {
		$args['column_width'] = $column_width;
	}

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

	if ( ! empty( $block_centered ) ) {
		$classes[] = $grid['columns']['medium'] . '-centered';
	}

	$classes = apply_filters( 'mesh_column_classes', $classes, $block_id );

	// Merge our block classes (from the input field).
	if ( ! empty( $block_css_class ) ) {
		$block_css_class = explode( ' ', $block_css_class );
		$classes         = array_merge( $classes, $block_css_class );
	}

	$classes = array_map( 'sanitize_html_class', $classes );
	$classes = array_unique( $classes );

	printf( 'class="%s"', join( ' ', $classes ) ); // WPCS: XSS ok, Sanitization ok.
}

/**
 * Get our default options for tinymce
 *
 * @since 1.2.5
 *
 * @return array
 */
function mesh_get_tinymce_defaults() {
	$tinymce_defaults = array(
		'wp_skip_init'          => false,
		'resize'                => false,
		'wordpress_adv_hidden'  => true,
		'add_unload_trigger'    => false,
		'statusbar'             => true,
		'autoresize_min_height' => 150,
		'wp_autoresize_on'      => false,
		'wpautop'               => true,
		'plugins'               => 'lists,media,paste,tabfocus,wordpress,textcolor,wpautoresize,wpeditimage,wpgallery,wplink,wptextpattern,wpview',
		'block_formats'         => 'Paragraph=p; Heading 3=h3; Heading 4=h4',
		'toolbar1'              => 'bold,italic,bullist,numlist,hr,alignleft,aligncenter,alignright,alignjustify,link,wp_adv ',
		'toolbar2'              => 'formatselect,underline,strikethrough,forecolor,pastetext,removeformat ',
		'toolbar3'              => '',
		'toolbar4'              => '',

		// @since 1.2.5 Change which options are shown when we are viewing smaller columns
		'small_toolbar1'        => 'bold,italic,bullist,numlist,link,wp_adv ',
		'small_toolbar2'        => 'hr,alignleft,aligncenter,alignright,alignjustify,formatselect,underline,strikethrough,forecolor,pastetext,removeformat',
	);

	$tinymce_defaults = apply_filters( 'mesh_tiny_mce_options', $tinymce_defaults );

	return $tinymce_defaults;
}

/**
 * Determine whether or not to show the title for a section or block
 *
 * @since 1.2.5
 *
 * @param string $post_id
 * @param        $title
 *
 * @return bool
 */
function mesh_maybe_show_section_title( $post_id = '', $title = '' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$title_display = get_post_meta( $post_id, '_mesh_title_display', true );

	if ( empty( $title_display ) || false === $title_display ) {
		return false;
	}

	if ( empty( $title ) ) {
		$title = $post->post_title;
	}

	$section_title_pattern = '/(no (section|column) title)( - ([0-9]*))?/mi'; // Match both old format and new format including - POST_ID also match column or sections

	preg_match_all( $section_title_pattern, $title, $section_title_matches, PREG_SET_ORDER, 0 );

	if ( empty( $title ) ) {
		return false;
	}

	if ( ! empty( $section_title_matches[1] ) ) {
		return false;
	}

	return true;
}

/**
 * Determine if we should show our block title or not.
 *
 * @since 1.2.5
 *
 * @param string $title
 *
 * @return bool
 */
function mesh_maybe_show_block_title( $title = '' ) {

	$section_title_pattern = '/(no (section|column) title)( - ([0-9]*))?/mi'; // Match both old format and new format including - POST_ID also match column or sections

	preg_match_all( $section_title_pattern, $title, $section_title_matches, PREG_SET_ORDER, 0 );

	if ( empty( $title ) ) {
		return false;
	}

	if ( ! empty( $section_title_matches[0][1] ) ) {
		return false;
	}

	return true;
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
		$post_id = $post->ID;
	}

	if ( has_post_thumbnail( $post_id ) ) {

		$backgrounds = array();

		$mesh_options = get_option( 'mesh_settings', array(
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
						$backgrounds[]     = sprintf( $interchange_format, $default_image_url[0], 'default' );
					}

					$small_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size_medium );

					if ( ! empty( $small_image_url ) ) {
						if ( ! empty( $small_image_url[0] ) && '' !== $small_image_url[0] ) {
							if ( ! in_array( $small_image_url[0], $background_urls, true ) ) {
								$background_urls[] = $small_image_url[0];
								$backgrounds[]     = sprintf( $interchange_format, $small_image_url[0], 'small' );
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
								$backgrounds[]     = sprintf( $interchange_format, $large_image_url[0], 'large' );
							}
						}
					}

					$xlarge_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size_xlarge );

					if ( ! empty( $xlarge_image_url ) ) {
						if ( ! empty( $xlarge_image_url[0] ) && '' !== $xlarge_image_url[0] ) {
							if ( ! in_array( $xlarge_image_url[0], $background_urls, true ) ) {
								$background_urls[] = $xlarge_image_url[0];
								$backgrounds[]     = sprintf( $interchange_format, $xlarge_image_url[0], 'xlarge' );
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
		'iframe'   => array(
			'src'                   => true,
			'style'                 => true,
			'id'                    => true,
			'class'                 => true,
			'name'                  => true,
			'allowfullscreen'       => true,
			'msallowfullscreen'     => true,
			'mozallowfullscreen'    => true,
			'webkitallowfullscreen' => true,
			'oallowfullscreen'      => true,
			'allowtransparency'     => true,
			'frameborder'           => true,
			'scrolling'             => true,
			'width'                 => true,
			'height'                => true,
		),
		'script'   => array(
			'src' => true,
		),
		'div'      => array(
			'data-equalizer'         => true,
			'data-equalizer-watch'   => true,
			'data-interchange'       => true,
			'data-lp-equal'          => true,
			'data-lp-equal-items'    => true,
			'data-lp-equal-children' => true,
		),
		'section'  => array(
			'data-interchange' => true,
		),
		'span'     => array(
			'class'                  => true,
			'style'                  => true,
			'id'                     => true,
			'data-equalizer'         => true,
			'data-equalizer-watch'   => true,
			'data-interchange'       => true,
			'data-lp-equal'          => true,
			'data-lp-equal-items'    => true,
			'data-lp-equal-children' => true,
		),
		'input'    => array(
			'align'           => true,
			'type'            => true,
			'name'            => true,
			'class'           => true,
			'id'              => true,
			'list'            => true,
			'value'           => true,
			'required'        => true,
			'placeholder'     => true,
			'checked'         => true,
			'disabled'        => true,
			'max'             => true,
			'min'             => true,
			'maxlength'       => true,
			'size'            => true,
			'hidden'          => true,
			'aria-required'   => true,
			'aria-labelledby' => true,
			'aria-invalid'    => true,
			'aria-checked'    => true,
		),
		'option'   => array(
			'value' => true,
		),
		'textarea' => array(
			'maxlength'       => true,
			'placeholder'     => true,
			'required'        => true,
			'aria-required'   => true,
			'aria-labelledby' => true,
			'aria-invalid'    => true,
		),
		'select'   => array(
			'name'            => true,
			'disabled'        => true,
			'multiple'        => true,
			'required'        => true,
			'size'            => true,
			'aria-required'   => true,
			'aria-labelledby' => true,
			'aria-invalid'    => true,
		),
		'fieldset' => array(
			'name'     => true,
			'disabled' => true,
		),
	) );

	$post_allowed = wp_kses_allowed_html( 'post' );

	return apply_filters( 'mesh_allowed_html', array_merge_recursive( $post_allowed, $mesh_allowed ) );
}
