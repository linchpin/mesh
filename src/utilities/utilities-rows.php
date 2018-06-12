<?php

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