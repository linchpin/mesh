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
 * @param int    $post_id Current Post ID.
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
 * @param int  $post_id Post ID.
 * @param string $return_type
 *
 * @return array|mixed|string
 */
function mesh_get_element_attributes( $post_id = '', $return_type = 'string' ) {
	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
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
 * @param int  $post_id Current Post ID.
 * @param bool $echo    Echo the post or not.
 * @since 1.2
 *
 * @return array|string
 */
function mesh_default_section_attributes( $post_id = 0, $echo = true ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id  = $post->ID;
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

function mesh_section_attributes( $post_id = '', $return_type = 'string' ) {
	$section_attributes = mesh_get_section_attributes( $post_id, $return_type );

	echo esc_html( $section_attributes );
}

/**
 * Middle man method to return the attributes used for columns.
 *
 * @since 1.2.5
 *
 * @param int    $post_id Post ID.
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

	$lp_equal = get_post_meta( get_the_ID(), '_mesh_lp_equal', true );

	if ( ! empty( $lp_equal ) ) {
		$column_attributes['data-equalizer-watch'] = '';
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
 * Middle man method to return the attributes used for rows.
 *
 * @since 1.2.5
 *
 * @param int    $post_id Post ID.
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
		$row_attributes['data-equalizer'] = '';
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
 * @param        $post_id
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
 * @param string $post_id
 *
 * @return string
 */
function mesh_get_row_class( $post_id = '' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$grid_system = mesh_get_responsive_grid();

	$custom_row_class        = explode( ' ', get_post_meta( $post_id, '_mesh_row_class', true ) );
	$collapse_column_spacing = get_post_meta( $post_id, '_mesh_collapse', true );

	$row_class   = array( 'mesh-row' );
	$row_class[] = $grid_system['row_class'];

	if ( ! empty( $collapse_column_spacing ) ) {
		$row_class[] = 'collapse';
	}

	if ( ! empty( $custom_row_class ) ) {
		$row_class = array_merge( $row_class, $custom_row_class );
	}

	$row_class = array_map( 'sanitize_html_class', $row_class ); // Sanitize each class we were passed.

	return trim( implode( ' ', $row_class ) );
}

/**
 * Get the title classes
 *
 * @since 1.2.5
 *
 * @param int $post_id
 *
 * @return string
 */
function mesh_get_title_class( $post_id = '' ) {

	global $post;

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}

	$mesh_options = get_option( 'mesh_settings' );

	$grid_system = mesh_get_responsive_grid( $mesh_options['grid_system'] );

	$title_display = get_post_meta( $post_id, '_mesh_title_display', true );

	$title_class = array(
		'small-12', // @todo this should be filterable or part of the grid system
		$grid_system['column_class'],
		'title-row',
	);

	if ( ! empty( $title_display ) ) {
		$title_class[] = $title_display;
	}

	$title_class = apply_filters( 'mesh_section_title_css_classes', $title_class );

	$title_class = array_map( 'sanitize_html_class', $title_class ); // Sanitize each class we were passed.

	return implode( ' ', $title_class );
}
