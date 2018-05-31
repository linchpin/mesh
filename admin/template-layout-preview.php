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

$is_active = '';

if ( ! empty( $default_template ) ) {
	$is_active = ' active';
}

$mesh_controls  = new Mesh_Controls();
$block_settings = $mesh_controls->get_block_settings();

?>
<div class="mesh-template-layout <?php echo esc_attr( $is_active ); ?>">
	<?php foreach ( $layout as $key => $row ) : ?>
		<div class="mesh-row">
			<?php
			$offsets_available     = 9;
			$section_blocks        = $row['blocks'];
			$block_count           = count( $section_blocks );
			$default_block_columns = $block_settings['max_columns'] / $block_count;
			$block_increment       = 0;

			// Loop through the blocks needed for this template.
			while ( $block_increment < $block_count ) :

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

				$block_offset   = $section_blocks[ $block_increment ]['offset'];
				$block_centered = ( isset( $section_blocks[ $block_increment ]['centered'] ) && true === $section_blocks[ $block_increment ]['centered'] ) ? 'centered' : '';

				if ( ! empty( $block_offset ) ) {
					$block_columns += $block_offset;
				}

				?>

				<div class="mesh-section-block mesh-columns-<?php echo esc_attr( $block_columns ); ?> columns <?php echo esc_attr( $block_centered ); ?>">
					<div class="block">
						<?php

						$offset = '';

						if ( 4 !== $section_blocks && $block_offset ) {
							$offset = ' mesh-has-offset mesh-offset-' . $block_offset;
						}
						?>
						<div class="block-content <?php esc_attr( $offset ); ?>">
						</div>
					</div>
				</div>
				<?php
				$block_increment++;
			endwhile;
			?>
		</div>
	<?php endforeach; ?>

	<?php if ( ! empty( $mesh_template_selectable ) && ! empty( $mesh_template_id ) && ! empty( $mesh_template_title ) ) : ?>
		<label for="mesh_template_<?php echo esc_attr( $mesh_template_id ); ?>"><?php echo esc_html( $mesh_template_title ); ?></label>
		<?php

		$checked = '';

		if ( ! empty( $default_template ) ) {
			$checked = 'checked';
		}
		?>
		<input id="mesh_template_<?php echo esc_attr( $mesh_template_id ); ?>" class="mesh-template" type="radio" name="mesh_template" value="<?php echo esc_attr( $mesh_template_id ); ?>" <?php echo esc_attr( $checked ); ?> />
	<?php endif; ?>
</div>
