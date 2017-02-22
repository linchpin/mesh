<?php
/**
 * Template to display mesh Layouts
 *
 * This template is utilizes in multiple areas of the admin.
 * Template preview and template selection
 *
 * @package    Mesh
 * @subpackage AdminTemplates
 * @since      1.1
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
	exit;
}
?>
<div class="mesh-template-layout<?php if ( ! empty( $default_template ) ) : ?> active<?php endif; ?>">
	<?php foreach ( $layout as $key => $row ) : ?>
		<div class="mesh-row">
			<?php
			$offsets_available = 9;

			$section_blocks = $row['blocks'];

			$default_block_columns = 12 / count( $section_blocks );

			// Loop through the blocks needed for this template.
			$block_increment = 0;

			while ( $block_increment < count( $section_blocks ) ) :

				if ( empty( $section_blocks[ $block_increment ] ) ) {
					continue;
				}

				$block_columns = $section_blocks[ $block_increment ]['columns'];

				/**
				 * Get how wide our column is. If no width is defined fall back to the default for that template.
				 * If no blocks are defined fall back to a 12 column.
				 */
				if ( empty( $block_columns ) ) {
					$block_columns = $default_block_columns;
				}

				$block_offset = $section_blocks[ $block_increment ]['offset'];

				if ( ! empty( $block_offset ) ) {
					$block_columns += $block_offset;
				}

				?>

				<div class="mesh-section-block mesh-columns-<?php esc_attr_e( $block_columns ); ?> columns">
					<div class="block">
						<div class="block-content <?php if ( 4 !== $section_blocks && $block_offset ) { esc_attr_e( ' mesh-has-offset mesh-offset-' . $block_offset ); } ?>">
						</div>
					</div>
				</div>
				<?php $block_increment++;
			endwhile; ?>
		</div>
	<?php endforeach; ?>

	<?php if ( ! empty( $mesh_template_selectable ) && ! empty( $mesh_template_id ) && ! empty( $mesh_template_title ) ) : ?>
		<label for="mesh_template_<?php esc_attr_e( $mesh_template_id ); ?>"><?php esc_html_e( $mesh_template_title ); ?></label>
		<input id="mesh_template_<?php esc_attr_e( $mesh_template_id ); ?>" class="mesh-template" type="radio" name="mesh_template" value="<?php esc_attr_e( $mesh_template_id ); ?>" <?php if ( ! empty( $default_template ) ) : ?> checked<?php endif; ?> />
	<?php endif; ?>
</div>
