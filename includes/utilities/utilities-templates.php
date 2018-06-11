<?php
/**
 * Utilities revolving around Mesh Templates
 */

/**
 * Get our available mesh templates
 *
 * @since 1.1
 * @param string $return_type Query or Array.
 * @param array  $statuses    Publish, Draft.
 *
 * @return array|\WP_Query
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
		case 'query':
			return $template_query;
		case 'array':
		default:
			return $template_query->posts;
	}
}