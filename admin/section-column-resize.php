<?php
/**
 * Template used to display helpful column resizer.
 *
 * Column resizing should only be done if we have more than 1 block but less than 4
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 * @since      1.0.0
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}

// If the template doesn't have any blocks make sure it has 1.
if ( ! $section_blocks = (int) $templates[ $selected_template ]['blocks'] ) {
	$section_blocks = 1;
}
if ( (int) $section_blocks > 1 ) :

	$default_block_columns = 12 / $section_blocks;

	// Loop through the blocks needed for this template.
	$block_increment = 0;

	$block_sizes = array();

	while ( $block_increment < $section_blocks ) {

		$block_columns = get_post_meta( $blocks[ $block_increment ]->ID, '_mesh_column_width', true );

		// Get how wide our column is.
		// If no width is defined fall back to the default for that template.
		// If no blocks are defined fall back to a 12 column.
		if ( empty( $block_columns ) || 1 === $templates[ $selected_template ]['blocks'] ) {
			$block_columns = $default_block_columns;
		}

		$block_sizes[] = (int) $block_columns;

		$block_increment++;
	}
endif;
if ( (int) $section_blocks > 1 && (int) $section_blocks < 4 ) : ?>
	<div class="wp-slider column-slider mesh-hide-for-small" data-mesh-blocks="<?php esc_attr_e( $section_blocks ); ?>" data-mesh-columns="<?php esc_attr_e( wp_json_encode( $block_sizes ) ); ?>"><span class="ui-slider-handle ui-state-default ui-corner-all fade-in-on-create hide" tabindex="0"></span></div>
<?php endif;
